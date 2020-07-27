<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
class OpenController extends Controller
{
    /*
     *用对称加密
     * openssl_encrypt
     * 解密
     * openssl_decrypt
     */
    public function encrypt(){
        $data  = "hello world";    //待加密的明文信息数据。
        $method = "AES-128-CBC";    //密码学方式。openssl_get_cipher_methods() 可获取有效密码方式列表。
        $key = "api1911";           //key。
       //options 是以下标记的按位或： OPENSSL_RAW_DATA 、 OPENSSL_ZERO_PADDING。
        $iv = "aaaabbbbccccdddd";   //非 NULL 的初始化向量。
        echo "<pre>";echo "原始 ：".$data;echo "</pre>";
        $name = openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);   //echo openssl_error_string();die;用来解决问题
        echo "<pre>";echo "加密：".$name;echo "</pre>";
        $desc = openssl_decrypt($name,$method,$key,OPENSSL_RAW_DATA,$iv);
        echo "<pre>";echo "解密：".$desc;echo "</pre>";
    }


    //对称加密接口
    public function openssl(){
        $data  = request()->get("data");    //待加密的明文信息数据。
        $datas = base64_decode($data);
        $name = $this->openssl_decrytp($datas);
        print_r($name);
    }

    //非对称加密接口
    public function aesc1(){
        $name = request()->get('name');
        //转码
        $names = base64_decode($name);
        // $name 这将保存加密的结果。
        $contents = file_get_contents(storage_path("keys/prin.key"));
        $key = openssl_get_privatekey($contents);
//        dd($key);
        //密钥解密
        openssl_private_decrypt($names,$data,$key,OPENSSL_PKCS1_OAEP_PADDING);
        dump($data);
    }

    //非对称加密
    public function desc2(){
        dd("kfjdh ");
    }




    //用公钥解密签名
    public function desc1(){
        $name = request()->get("name");
        $data = request()->get("data");
        $names = base64_decode($name);
        $key = file_get_contents(storage_path("keys/www.pub.key"));

        $datas = openssl_verify($data,$names,$key,OPENSSL_ALGO_SHA1);
        if($datas == 1){
            echo "OK";
        }else if($datas == 0){
            echo "NO";
        }else{
            echo "内部发生了错误";
        }
    }

    //验证签名
    public function desc(){
        $data = "留得青山在，不怕没柴烧";
        $key = '1911_api';
        $shal = sha1($data.$key);
        $url = "http://www.1911.com/user/desc?data=".$data."&shal=".$shal;
        $name = file_get_contents($url);
        echo $name;

    }


    //签名方式
    public function desc3(){
        $name = "12345";
        $token = "Zhaoyalin";
        $url = "http://www.1911.com//user/desc2";
        $heaters = [
            "name".$name,
            "token".$token,
        ];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$heaters);
        curl_exec($ch);
        curl_close($ch);
    }









    /*
    *用凯撒加密
    *$data   $method   $key   OPENSSL_RAW_DATA    $iv//注意（这个对多是16位）
     *待加密的明文信息数据
     *密码学方式。openssl_get_cipher_methods() 可获取有效密码方式列表。
     * //非 NULL 的初始化向量。
     */
    public function openssl_encrytp($data,$key="api1911",$iv="aaaabbbbccccdddd",$method="AES-128-CBC"){
        $name = openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
        return $name;
    }
    /*
       *用凯撒解密
       *$data   $method   $key   OPENSSL_RAW_DATA    $iv//注意（这个对多是16位）
        *加密的明文信息数据
        *密码学方式。openssl_get_cipher_methods() 可获取有效密码方式列表。
        * //非 NULL 的初始化向量。
     */
    public function openssl_decrytp($data,$key="api1911",$iv="aaaabbbbccccdddd",$method="AES-128-CBC"){
        $name = openssl_decrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
        return $name;
    }


































}
