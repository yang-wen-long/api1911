<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        echo "<pre>";echo "原始：".$data;echo "</pre>";
        $name = openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);   //echo openssl_error_string();die;用来解决问题
        echo "<pre>";echo "加密：".$name;echo "</pre>";

        $desc = openssl_decrypt($name,$method,$key,OPENSSL_RAW_DATA,$iv);
        echo "<pre>";echo "解密：".$desc;echo "</pre>";
    }


    //加密接口
    public function openssl(){
        dd("jgk");
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
