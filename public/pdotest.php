<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

$insert_sth = $dbh->prepare("INSERT INTO mayano (ip,ua) VALUES (:ip,:ua)");

$insert_sth->execute([
    ':ip' => $_SERVER['REMOTE_ADDR'],
    ':ua' => $_SERVER['HTTP_USER_AGENT']
]);

$hyoji = $dbh->prepare("SELECT * FROM mayano");
$hyoji->execute();
$data = $hyoji->fetch();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

<table class="table"><thead>
 <tr><th scope="col">ip</th><th scope="col">userAgent</th><th scope="col">date</th></tr>
 </thead>
<?php
while($row = $hyoji->fetch(PDO::FETCH_ASSOC)){
?>
<tbody>
<tr>
  <th><?=htmlspecialchars($row['ip'])?></th>
  <th><?=htmlspecialchars($row['ua'])?></th>
  <th><?=htmlspecialchars($row['dai'])?></th>
</tr>
<?php
}
?>
</tbody><table>
