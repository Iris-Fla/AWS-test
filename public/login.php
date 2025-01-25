<?php
// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');
if (!empty($_POST['email']) && !empty($_POST['password'])) {
  // POSTで email と password が送られてきた場合のみログイン処理をする
  // email から会員情報を引く
  $select_sth = $dbh->prepare("SELECT * FROM users WHERE email = :email ORDER BY id DESC LIMIT 1");
  $select_sth->execute([
    ':email' => $_POST['email'],
  ]);
  $user = $select_sth->fetch();
  if (empty($user)) {
    // 入力されたメールアドレスに該当する会員が見つからなければ、処理を中断しエラー用クエリパラメータ付きのログイン画面URLにリダイレクト
    header("HTTP/1.1 302 Found");
    header("Location: ./login.php?error=1");
    return;
  }
  // パスワードが正しいかチェック
  $correct_password = password_verify($_POST['password'], $user['password']);
  if (!$correct_password) {
    // パスワードが間違っていれば、処理を中断しエラー用クエリパラメータ付きのログイン画面URLにリダイレクト
    header("HTTP/1.1 302 Found");
    header("Location: ./login.php?error=1");
    return;
  }
  // PHPの標準セッション管理機能使う際は、まずこの session_start関数を呼ぶ必要がある
  session_start();
  // PHPのセッション管理機能を使い、ログインできた会員情報の主キー(id)を設定
  $_SESSION["login_user_id"] = $user['id'];
  // ログインが成功したらログイン完了画面にリダイレクト
  header("HTTP/1.1 302 Found");
  header("Location: ./login_finish.php");
  return;
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<div class="container">
<h1 class="text-center">ログイン</h1>
<!-- ログインフォーム -->
<form method="POST">
  <!-- input要素のtype属性は全部textでも動くが、適切なものに設定すると利用者は使いやすい -->
  <label class="form-label">
    メールアドレス:
    <input type="email" name="email" class="form-control">
  </label>
  <br>
  <label class="form-label">
    パスワード:
    <input type="password" name="password" minlength="6" class="form-control">
  </label>
  <br>
  <button type="submit" class="btn btn-info">ログイン</button>
</form>
<a href="/signup.php">登録はこちら</a>
<?php if(!empty($_GET['error'])): // エラー用のクエリパラメータがある場合はエラーメッセージ表示 ?>
<div style="color: red;">
  メールアドレスかパスワードが間違っています。
</div>
</div>
<?php endif; ?>