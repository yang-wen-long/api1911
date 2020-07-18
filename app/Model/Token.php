<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    //指定表名
    protected $table = "token";
    //指定主键pk
    protected $primaryKey = "id";
    //关闭时间戳
    public $timestamps = false;
    //黑名单
    protected $guarded = [];
}
