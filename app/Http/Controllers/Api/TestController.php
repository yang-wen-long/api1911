<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
//redis
//$asc =  Redis::lrem("goods",$name,"1");
use Illuminate\Support\Facades\Redis;
class TestController extends Controller
{
    //
    public function index(){
        $appid = "wx57336fc970e851dd";
        $app = "60055b853d5141421d3b33f92846f420";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$app;
        $resqure = file_get_contents($url);
        dd($resqure);
    }

    public function info(){
        echo "杀浮动空间几何空间";
    }





    //商品的抢购
    public function goods(){
        //添加购买数量
       //$name = Redis::lpush("goods",1,1,1,1,1,1,1,1,1);
        //购买数量
        $name = request()->get("goods");
        if(empty($name)){
            $redis = [
                "msg" => 40001,
                "error" => "购买数量不能为空",
            ];
            return $redis;
        }
        //查询数据是否够买
        $desc = Redis::llen("goods");
        if($desc == 0){
            $redis = [
                "msg" => 40002,
                "error" => "抢购数量已完成",
            ];
            return $redis;
        }
        //判断是否够买家购买
        if($name > $desc){
            $redis = [
                "msg" => 40002,
                "error" => "购买的库存不足！剩余$desc 个",
            ];
            return $redis;
        }
        if($name > 1){
            $asc =  Redis::lrem("goods",$name,"1");
//            $method = $this->Llen($name);
//            dd($method);
        }else{
            $asc = Redis::lpop("goods");
        }

        //查询剩余几条数据
        $ttl = Redis::llen("goods");
        $redis = [
            "msg" => 0,
            "error" => "抢购成功,剩余".$ttl."个",
        ];
        return $redis;
    }

    //删除多条购买数量
    private function Llen($name){
        if($name !=1){

        }else{

        }

    }











}
