<?php
$redis = new Redis();
$redis->connect("redis", 6379);

$text = $redis->get('text');

if (isset($_POST['body'])) {

    $redis -> set("text",$_POST['body']);
    header("HTTP/1.1 302 Found");
    header("Location: ./redispost.php");
    return;
  }

?>
<form method="POST" action="./redispost.php">
    <textarea name="body" placeholder="投稿内容を入力してください"></textarea>
    <button type="submit">送信</button>
</form>

<hr/>

<p>現在の内容</p>
<p><?= $text ?></p>