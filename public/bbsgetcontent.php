<?php
header("Content-type: application/json; charset=UTF-8");
// DB接続
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');
// コンテンツ件数
$count = $_POST["count"];
 
// SQL文生成
$sql = 'SELECT bbs_entries.*, users.name AS user_name ,users.icon_filename'
  . ' FROM bbs_entries INNER JOIN users ON bbs_entries.user_id = users.id'
  . ' ORDER BY bbs_entries.created_at DESC'
 
// 実行結果取得
$stmt = $dbh->query($sql); 
// 配列取得
$content_arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
echo json_encode($content_arr);
exit;
?>