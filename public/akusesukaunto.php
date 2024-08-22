<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

$mutukikawaii = $dbh->prepare("UPDATE mutuki SET count = count + 1");

$res = $mutukikawaii->execute();

$mutumutu = $dbh->prepare("SELECT count FROM mutuki");

$res2 = $mutumutu->execute();
$data = $mutumutu->fetch();
print "アクセスカウント $data[0] やで";
?>
