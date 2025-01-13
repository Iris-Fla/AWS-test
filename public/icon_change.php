<?php
session_start();

if (empty($_SESSION['login_user_id'])) {
    header("HTTP/1.1 302 Found");
    header("Location: ./login.php");
    return;
}

$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

if (isset($_FILES['image'])){
    if (preg_match('/^image\//', mime_content_type($_FILES['image']['tmp_name'])) !== 1) {
        // アップロードされたものが画像ではなかった場合
        header("HTTP/1.1 302 Found");
        header("Location: ./iconchange.php?failed=1");
        return;
    }
    // 元のファイル名から拡張子を取得
    $pathinfo = pathinfo($_FILES['image']['name']);
    $extension = $pathinfo['extension'];
    
    // 新しいファイル名を決める。他の投稿の画像ファイルと重複しないように時間+乱数で決める。
    $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
    $filepath =  '/var/www/upload/image/' . $image_filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
    $new_icon = $dbh->prepare("UPDATE users SET icon_filename=:icon_filename WHERE id = :id");
    $new_icon ->execute([
        ':id' => $_SESSION['login_user_id'],
        ':icon_filename' => $image_filename,
    ]);
    header("HTTP/1.1 302 Found");
    header("Location: /icon_change.php?iconchange=1");
    return;
}

$insert_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
$insert_sth->execute([
    ':id' => $_SESSION['login_user_id'],
]);
$user = $insert_sth->fetch();
?>

<div>いまの名前</div>
<h3><?= htmlspecialchars($user['name']) ?></h3>


<h2>アイコン変えるならこち</h2>
<?php if(isset($user['icon_filename'])):?>
<div>
    おまえ、これ?
<img src="/image/<?= $user['icon_filename'] ?>" style="max-height: 10em;">
</div>
<?php endif;?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" accept="image/*" name="image">
    <button type="submit">送信</button>
</form>