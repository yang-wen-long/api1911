<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
//随机数的
use Illuminate\Support\Str;
//Token
use App\Model\Token;
use Illuminate\Support\Facades\Redis;
//商品表
use App\Model\goods;
class LoginController extends Controller
{
    //注册展示方法
    public function Reg(){
       $name = request()->get("user_name");
       $password = request()->get("password");
       $Email = request()->get("email");
       $time = time();
       if(empty($name)){
           $redisce = [
               "error" => 40001,
               "msg" => "账户不能为空"
           ];
           return $redisce;
       }
       if(empty($password)){
                $redisce = [
                    "error" => 40002,
                    "msg" => "密码不能为空"
                ];
                return $redisce;
        }
        if(empty($Email)){
            $redisce = [
                "error" => 40003,
                "msg" => "Email不能为空"
            ];
            return $redisce;
        }
        $user = User::where("user_name",$name)->first();
        if($user){
            $redisce = [
                "error" => 40004,
                "msg" => "账号已存在"
            ];
            return $redisce;
        }
        $password = encrypt($password);
        $user = new User;
        $user->user_name = $name;
        $user->password = $password;
        $user->Email = $Email;
        $user->time = $time;
        $desc = $user->save();
        if($desc){
            $redisce = [
                "error" => 0,
                "msg" => "注册成功",
                "data" => "http://api.1911.com/user/Login",
            ];
            return $redisce;
        }else{
            $redisce = [
                "error" => 40005,
                "msg" => "注册失败，页面有误"
            ];
            return $redisce;
        }
    }
    //登录方法
    public function Login(){
        $name = request()->get("user_name");
        $pwd = request()->get("password");
        if(empty($name)){
            $redisce = [
                "error" => 40001,
                "msg" => "账号不能为空",
            ];
            return $redisce;
        }
        if(empty($pwd)){
            $redisce = [
                "error" => 40002,
                "msg" => "密码不能为空"
            ];
            return $redisce;
        }
        //根据条件查询用户数据
        $user = User::where("user_name",$name)->first();
        if(!$user){
            $redisce = [
                "error" => 40006,
                "msg" => "密码或账号有误，请重新填写！"
            ];
            return $redisce;
        }
        if(decrypt($user->password) !== $pwd){
            $redisce = [
                "error" => 40007,
                "msg" => "密码或账号有误，请重新填写！"
            ];
            return $redisce;
        }
        //添加次数
        Redis::incr("name");
        //查询次数
        $name = Redis::get("name");
        if($name > 20){
            $redisce = [
                "error" => 40009,
                "msg" => "请求Token次数超限",
            ];
            return $redisce;
        }
        //Token的过期时间
        $tim = "7200";
        //回馈用户的随机数
        $Token = Str::random(32);

        //********************************添加数据库
        $tokenname = new Token;
        $tokenname->token = $Token;
        $tokenname->user_id = $user->user_id;
        $tokenname->time = time() + $tim;
        $tokenname->save();
        //****************************************************
        $redisce = [
            "error" => 0,
            "msg" => "OK",
            "access_token" =>$Token,
            "expires_in" =>$tim,
        ];
        return $redisce;



    }
    //个人中心显示用户信息
    public function center(){
        $token = request()->get("token");
        $name = Token::OrderBY("id","desc")->where("token",$token)->first();
        $user = User::where("user_id",$name->user_id)->first();
        if($user){
            $redisce = [
                "error" => 0,
                "msg" => "OK",
                "data" => [
                    "user_name" => "$user->user_name",
                    "Email" => "$user->Email",
                    "time" => "$user->time"
                ]
            ];
            return $redisce;
        }
    }

    //使用Redis有序集合实现签到功能：
    public function qiandao(){
        $token = request()->get("token");
        $name = Token::OrderBY("id","desc")->where("token",$token)->first();
        //判断用户是否存在
        if(!$name){
            $redisce = [
                "error" => 40007,
                "msg" => "未经授权",
            ];
            return $redisce;
        }
        //检查token是否过期
        if($name->time < time()){
            $redisce = [
                "error" => 40008,
                "msg" => "Token 已过期",
            ];
            return $redisce;
        }
        $key = "$token".$name->time;
        $namedesc = $name->user_name;
        $id = $name->user_id;
        $nameset = Redis::get($key);
        if($nameset){
            $redisce = [
                "error" => 40009,
                "msg" => "今天已签到，请等明天在签到!",
            ];
            return $redisce;
        }else{
            Redis::set($key,$id);
            Redis::expire($key,172800);
            $Thesame = Redis::zscore($key, $namedesc);
            if ($Thesame) {
                $names = Redis::zincrby($key, 1, $namedesc);
                $namekey = $names;
                $redisce = [
                    "error" => 0,
                    "msg" => "签到成功，已签到" . $namekey . "天",
                ];
                return $redisce;
            }else {
                Redis::zadd($key, 1, $namedesc);
                $redisce = [
                    "error" => 0,
                    "msg" => "签到成功，已签到1天",
                ];
                return $redisce;
            }
        }
    }
    //用redis缓存用户信息
    public function Signin(){
        //接过来的参数
        $goods_id = request()->get("goods_id");
        //拼接key
        $key = "H:goods_info:".$goods_id;
        //根据传过来的id进redis进行查询
        $goods_info = Redis::hgetAll($key);
        //判断是否成功
        if(empty($goods_info)){
            $gey = goods::select("goods_id","goods_sn","goods_name","cat_id")->find($goods_id);
            $goods_info = $gey->toArray();
            $goods = Redis::hmset($key,$goods_info);
            echo "缓存";
            echo "<pre>";print_r($goods_info);echo "</pre>";
        }else{
            echo "不缓存";
            echo "<pre>";print_r($goods_info);echo "</pre>";
        }
        
    }

    //用户缓存
    public function user_name(){
        $Token = request()->get("token");
        $user_token = Token::where("token",$Token)->first();
        $user_name =  User::select("user_name","time","Email")->where("user_id",$user_token->user_id)->first();
        $key = $user_name->time.$user_name->Email;
        $keys  = $user_name->Email;
        $redis_name = Redis::hgetAll($key);
        if(empty($redis_name)){
            dump("缓存");
            /*App\Model\User {
                          #293
                          #table: "user"
                          #primaryKey: "user_id"
                          +timestamps: false
                          #guarded: []
                          #connection: "mysql"
                          #keyType: "int"
                          +incrementing: true
                          #with: []
                          #withCount: []
                          #perPage: 15
                          +exists: true
                          +wasRecentlyCreated: false
                          #attributes: array:3 [
                            "user_name" => "哑铃"
                            "time" => "1594977871"
                            "Email" => "2382662404@qq.com"
                          ]
                          #original: array:3 [
                            "user_name" => "哑铃"
                            "time" => "1594977871"
                            "Email" => "2382662404@qq.com"
                          ]
                          #changes: []
                          #casts: []
                          #dates: []
                          #dateFormat: null
                          #appends: []
                          #dispatchesEvents: []
                          #observables: []
                          #relations: []
                          #touches: []
                          #hidden: []
                          #visible: []
                          #fillable: []
              }
             */
            $user_names = $user_name->toArray();
            /*array:3 [
                      "user_name" => "哑铃"
                      "time" => "1594977871"
                      "Email" => "2382662404@qq.com"
              ]
             */
            Redis::hmset($key,$user_names);
            dd($user_names);
        }else{
            dump("不缓存");
            dd($redis_name);
        }
    }


    //使用Redis中Hash实现每个用户访问的接口统计
    public function stati(){
        //获取穿过来到值
        $token = request()->get("token");
        //根据值查询数据库
        $name = Token::OrderBY("id","desc")->where("token", $token)->first();
        //根据数据查询用户
        $names = User::where("user_id",$name->user_id)->first();
        //拼接条件
        $kye = $names->Email.$names->time;
        $name_desc = Redis::hlen($kye);
        if($name_desc > 1){
            //查询数据
            $name_desc = Redis::hgetAll($kye);
//            dd($name_desc);
//            foreach($name_desc as $k=>$a){
                //数组返回数据
                $redisce = [
                    "error" => 0,
                    "msg" => "查询成功",
                    "data" => [
                        $name_desc,
                    ],
                ];
                return $redisce;
//            }
        }else{
            //查询数据
            $name_desc = Redis::hgetAll($kye);
            //获取一维数组的下标
            $name_name = array_keys($name_desc);
            //获取一维数组的值
            $name_key = array_values($name_desc);
            //将数组去除前部分
            $name_descf = array_shift($name_name);
            //截取字符串从前20位开始截取
            $Fullpath = substr($name_descf,20);
            //将一维数组去除前部分
            $values = array_shift($name_key);
            //数组返回数据
            $redisce = [
                "error" => 0,
                "msg" => "查询成功",
                "data" => [
                    "$Fullpath" => $values,
                ],
            ];
            return $redisce;
        }
    }























}
