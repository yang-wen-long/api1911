<?php
//实例化redis
$redis = new Redis();
//链接redis
$redis->connect("127.0.0.1",6379);
$redis->auth("yang20004295258");
//取的下标
$k = "name";
echo $redis->get($k);








?>
