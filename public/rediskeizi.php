<?php
$redis = new Redis();
$redis->connect("redis", 6379);
if (isset($_POST['body'])) {
    
    $json = $redis ->get("jsontext");
    $arr = json_decode($json,true);

    array_push($arr,$_POST['body']);

    $arrJson = json_encode($arr);
    
    $redis -> set("jsontext",$arrJson);

    header("HTTP/1.1 302 Found");
    header("Location: ./rediskeizi.php");
    return;
  }
else {
    $json = $redis ->get("jsontext");
    $arr = json_decode($json,true);
}
?>
<form method="POST" action="./rediskeizi.php">
    <textarea name="body" placeholder="投稿内容を入力してください"></textarea>
    <button type="submit">送信</button>
</form>
<?php foreach($arr as $row): ?>
    <hr>
    <dl>
      <dt><?= nl2br(htmlspecialchars($row)) ?></dt>
    </dl>
<?php endforeach ?>
<hr>