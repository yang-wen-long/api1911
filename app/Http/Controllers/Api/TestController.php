<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
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












}
