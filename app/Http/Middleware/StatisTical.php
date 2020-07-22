<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Token;
use App\Model\User;
use Illuminate\Support\Facades\Redis;
class StatisTical
{
    /**
     * Handle an incoming request.
     *使用Redis中Hash实现每个用户访问的接口统计
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = request()->get("token");
        if($token) {
                $name = Token::OrderBY("id","desc")->where("token", $token)->first();
                //判断用户是否存在
                if (!$name) {
                    $redisce = [
                        "error" => 40007,
                        "msg" => "未经授权",
                    ];
    //               print_r($redisce);die;
                    echo json_encode($redisce, JSON_UNESCAPED_UNICODE);
                    die;
                }
                //检查token是否过期
                if ($name->time < time()) {
                    $redisce = [
                        "error" => 40008,
                        "msg" => "Token 已过期",
                    ];
    //               print_r($redisce);die;
                    echo json_encode($redisce, JSON_UNESCAPED_UNICODE);
                    die;
                }
                $names = User::where("user_id",$name->user_id)->first();
                //获取访问的当前路径
                $desc = request()->route()->getActionName();
                $field = "user:".$names->time."path:".$desc;
                $kye = $names->Email.$names->time;
                //查询
                $name_desc = Redis::hget($kye,$field);
                if($name_desc){
                    //自动递增
                    $Seslle = Redis::hincrby($kye,$field,1);
                }else{
                    //加一
                    $Seslle = Redis::hset($kye,$field,1);
                }
        }
        return $next($request);
    }
}
