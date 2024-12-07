<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');
if (isset($_POST['body'])) {
  // POSTで送られてくるフォームパラメータ body がある場合
  // insertする
  $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (body) VALUES (:body)");
  $insert_sth->execute([
      ':body' => $_POST['body'],
  ]);
  // 処理が終わったらリダイレクトする
  // リダイレクトしないと，リロード時にまた同じ内容でPOSTすることになる
  header("HTTP/1.1 302 Found");
  header("Location: ./bbstest.php");
  return;
}
if (isset($_GET['body'])) {
  // GEYで送られてくるフォームパラメータ body がある場合
  // insertする
  $gey = $_GET['body'];
  $select_sth = $dbh->prepare("SELECT * FROM bbs_entries WHERE body LIKE (:gey) ORDER BY created_at DESC");
  $select_sth->execute([
    ':gey' => "%" . $gey . "%",
  ]);
  $serch = True;
} 
else {
    // いままで保存してきたものを取得
    $select_sth = $dbh->prepare('SELECT * FROM bbs_entries ORDER BY created_at DESC');
    $select_sth->execute();
    $serch = False;
}
?>
<!-- フォームのPOST先はこのファイル自身にする -->
<form method="POST" action="./bbstest.php">
  <textarea name="body"></textarea>
  <button type="submit">送信</button>
</form>
<h2>調べろ</h2>
<form method="GET" action="./bbstest.php">
  <textarea name="body"></textarea>
  <button type="submit">検索</button>
</form>
<?php if($serch):?>
    <p>検索中のたんごは <?= $gey ?> だよー</p>
    <a href="./bbstest.php">検索解除だよ</a>
<?php endif; ?>
<hr>
<?php foreach($select_sth as $entry): ?>
  <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
    <dt>ID</dt>
    <dd><a href="./bbsdetail.php?id=<?= $entry['id']?>" target="_blank" rel="noopener noreferrer"><?= $entry['id'] ?></a></dd>
    <dt>日時</dt>
    <dd><?= $entry['created_at'] ?></dd>
    <dt>内容</dt>
    <dd><?= nl2br(htmlspecialchars($entry['body'])) // 必ず htmlspecialchars() すること ?></dd>
  </dl>
<?php endforeach ?>