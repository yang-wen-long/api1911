<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Token;
use Illuminate\Support\Facades\Redis;
class ViewModdle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = request()->get("token");
       if($token){
           $name = Token::OrderBY("id","desc")->where("token",$token)->first();
           //判断用户是否存在
           if(!$name){
               $redisce = [
                   "error" => 40007,
                   "msg" => "未经授权",
               ];
//               print_r($redisce);die;
               echo  json_encode($redisce,JSON_UNESCAPED_UNICODE);die;
           }
           //检查token是否过期
           if($name->time < time()){
               $redisce = [
                   "error" => 40008,
                   "msg" => "Token 已过期",
               ];
//               print_r($redisce);die;
               echo  json_encode($redisce,JSON_UNESCAPED_UNICODE);die;
           }
           //防止刷新
           $this->fang($name);
       }else{
           $redisce = [
               "error" => 40008,
               "msg" => "未授权",
           ];
//               print_r($redisce);die;
           echo  json_encode($redisce,JSON_UNESCAPED_UNICODE);die;
       }
        return $next($request);
    }
    //防止刷取信息
    public function fang($name){
        //加入黑名单
        // 获取 该用户的user_id  并根据用户的user_id 存入redis集合中
        $shuliang=redis::scard("Blacklist".$name->user_id);
        if($shuliang >= 10){
            $data=[
                "msg"=>1,
                "data"=>"尊敬的用户,我们检测您频繁调用该接口,我们怀疑在恶意刷取信息，故此封号1小时。"
            ];
            $jihe=redis::expire("Blacklist".$name->user_id,60);
            echo  json_encode($data,JSON_UNESCAPED_UNICODE);die;
        }

        if($shuliang==1){
            $jihe=redis::expire("Blacklist".$name->user_id,60);
        }

        $jihe=redis::sadd("Blacklist".$name->user_id,uniqid());
    }
}
