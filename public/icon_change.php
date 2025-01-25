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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<div class="container">
<h1>アイコン変更ページ</h1>
<a href="./bbs.php">掲示板に戻る</a>
<h3>ログイン中のアカウント:<strong><?= htmlspecialchars($user['name']) ?></strong></h3>

<?php if(isset($user['icon_filename'])):?>
<div>
    おまえ、これ?
<img src="/image/<?= $user['icon_filename'] ?>" style="max-height: 10em;">
</div>
<?php endif;?>

<div class="input-group">
<form method="POST" enctype="multipart/form-data" class="form-label">
    <input type="file" accept="image/*" name="image" class="form-control">
    <button type="submit" class="btn btn-info">送信</button>
</form>
</div>
</div>