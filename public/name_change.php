<?php
session_start();

if (empty($_SESSION['login_user_id'])) {
    header("HTTP/1.1 302 Found");
    header("Location: ./login.php");
    return;
}

$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

if (!empty($_POST['name'])) {
    $new_name = $dbh->prepare("UPDATE users SET name=:name WHERE id = :id");
    $new_name ->execute([
        ':id' => $_SESSION['login_user_id'],
        ':name' => $_POST['name'],
    ]);
    header("HTTP/1.1 302 Found");
    header("Location: /login_finish.php?namechange=1");
    return;
}

if (isset($_FILES['image'])){
    if (preg_match('/^image\//', mime_content_type($_FILES['image']['tmp_name'])) !== 1) {
        // アップロードされたものが画像ではなかった場合
        header("HTTP/1.1 302 Found");
        header("Location: ./namechange.php?failed=1");
        return;
    }
    // 元のファイル名から拡張子を取得
    $pathinfo = pathinfo($_FILES['image']['name']);
    $extension = $pathinfo['extension'];
    
    // 新しいファイル名を決める。他の投稿の画像ファイルと重複しないように時間+乱数で決める。
    $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
    $filepath =  '/var/www/upload/image/' . $image_filename;
    var_dump($filepath);
    move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
    $new_icon = $dbh->prepare("UPDATE users SET icon_filename=:icon_filename WHERE id = :id");
    $new_name ->execute([
        ':id' => $_SESSION['login_user_id'],
        ':icon_filename' => $filepath,
    ]);
    header("HTTP/1.1 302 Found");
    header("Location: /namechange.php?iconchange=1");
    return;
}

$insert_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
$insert_sth->execute([
    ':id' => $_SESSION['login_user_id'],
]);
$user = $insert_sth->fetch();

?>
<h1>お名前変えちゃう？？？？？</h1>
<div>いまの名前</div>
<h3><?= htmlspecialchars($user['name']) ?></h3>
<form method="POST">
    <label>
        新しい名前:
        <input type="text" name="name" placeholder="<?= htmlspecialchars($user['name']) ?>">
    </label>
    <br>
    <button type="submit">決定</button>
</form>


<a href="./login_finish.php">もどるで</a>