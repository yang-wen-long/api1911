<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class goods extends Model
{
    //指定表名
    protected $table = "p_goods";
    //指定主键pk
    protected $primaryKey = "goods_id";
    //关闭时间戳
    public $timestamps = false;
    //黑名单
    protected $guarded = [];
}
