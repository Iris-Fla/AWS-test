<?php
// セッション管理機能を使うため
session_start();
// セッションにログインIDが無ければ (=ログインされていない状態であれば) ログイン画面にリダイレクトさせる
if (empty($_SESSION['login_user_id'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./login.php");
  return;
}

// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

if (!empty($_POST['name'])) {
    $new_name = $dbh->prepare("UPDATE users SET name=:name WHERE id = :id");
    $new_name ->execute([
        ':id' => $_SESSION['login_user_id'],
        ':name' => $_POST['name'],
    ]);
    header("HTTP/1.1 302 Found");
    header("Location: /login_finish.php");
    return;
}
// セッションにあるログインIDから、ログインしている対象の会員情報を引く
$insert_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
$insert_sth->execute([
    ':id' => $_SESSION['login_user_id'],
]);
$user = $insert_sth->fetch();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<div class="container">
<h1>ログイン完了</h1>
<p>
  ログイン完了しました!
</p>
<hr>
<p>
  また、あなたが現在ログインしている会員情報は以下のとおりです。
</p>
<a href="/bbs.php">掲示板に戻る</a>
<dl> <!-- 登録情報を出力する際はXSS防止のため htmlspecialchars() を必ず使いましょう -->
  <dt>ID</dt>
  <dd><?= htmlspecialchars($user['id']) ?></dd>
  <dt>メールアドレス</dt>
  <dd><?= htmlspecialchars($user['email']) ?></dd>
  <dt>名前</dt>
  <dd><?= htmlspecialchars($user['name']) ?></dd>
  <?php if(!empty($_GET['namechange'])): ?>
  <h1 style="color: red;">
  👆👆👆👆見て！名前変わったよ！👆👆👆👆
  </h1>
  <?php endif; ?>
</dl>

<a href="./name_change.php">お名前...変えちゃう?</a>
<br>
<a href="./icon_change.php">アイコン変更はこちら</a>
</div>