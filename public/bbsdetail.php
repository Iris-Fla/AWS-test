<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');
if(isset($_GET['id'])) {$id = $_GET['id'];}
$select_sth = $dbh->prepare("SELECT * FROM bbs_entries WHERE id = (:id)  ORDER BY created_at DESC");
$select_sth->execute([
    ':id' => $id
]);
?>
<a href="./bbstest.php" target="_blank" rel="noopener noreferrer">もどる</a>
<?php foreach($select_sth as $entry): ?>
  <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
    <dt>ID</dt>
    <dd><?= $entry['id'] ?></dd>
    <dt>日時</dt>
    <dd><?= $entry['created_at'] ?></dd>
    <dt>内容</dt>
    <dd><?= nl2br(htmlspecialchars($entry['body'])) // 必ず htmlspecialchars() すること ?></dd>
  </dl>
<?php endforeach ?>