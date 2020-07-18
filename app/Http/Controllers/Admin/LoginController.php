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
        if($name > 10){
            $redisce = [
                "error" => 40009,
                "msg" => "请求Token次数超限",
            ];
            return $redisce;
        }
        dd($name);
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function text(){
//        Redis::set("desc","汉字");
//        Redis::expire("desc","60");
        if(time()+60){
            $name = "100";
            if($name > 10){
                dd("10");
            }else{
                dd("4");
            }
        }else{
            dd("你好");
        }
    }
    
    
    
    
    
    
    
    

}
