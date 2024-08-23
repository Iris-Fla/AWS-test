<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

$insert_sth = $dbh->prepare("INSERT INTO posts (content) VALUES (:content)");
$insert_sth->execute([
    ':content' => 'hello world!!!!!!!!!'
]);
print('insertできました');