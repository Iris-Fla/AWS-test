<?php
$redis = new Redis();
$redis->connect("redis", 6379);

$counter = $redis->get('counter');

$redis->set("counter",$counter + 1);

echo "おまえ、";
echo $counter;
echo "やで";