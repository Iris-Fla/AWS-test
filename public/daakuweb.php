<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

if (isset($_POST['body'])) {
  // POSTで送られてくるフォームパラメータ body がある場合

  // postsテーブルにINSERTする

  //SQLインジェクション対策してるはず。
  $insert_sth = $dbh->prepare("INSERT INTO posts (content) VALUES (:body)");
  $insert_sth->execute([
      ':body' => $_POST['body'],
  ]);

  // 処理が終わったらリダイレクトする
  // リダイレクトしないと，リロード時にまた同じ内容でPOSTすることになる
  header("HTTP/1.1 302 Found");
  header("Location: ./daakuweb.php");
  return;
}

// ページ数をURLクエリパラメータから取得。無い場合は1ページ目とみなす
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 1ページあたりの行数を決める
$count_per_page = 10;

// ページ数に応じてスキップする行数を計算
$skip_count = $count_per_page * ($page - 1);

// postsテーブルの行数を SELECT COUNT で取得
$count_sth = $dbh->prepare('SELECT COUNT(*) FROM posts;');
$count_sth->execute();
$count_all = $count_sth->fetchColumn();
if ($skip_count >= $count_all) {
    // スキップする行数が全行数より多かったらおかしいのでエラーメッセージ表示し終了
    print('このページは存在しません!');
    return;
}

// postsテーブルからデータを取得
$select_sth = $dbh->prepare('SELECT * FROM posts ORDER BY created_at DESC LIMIT :count_per_page OFFSET :skip_count');
// 文字列ではなく数値をプレースホルダにバインドする場合は bindParam() を使い，第三引数にINTであることを伝えるための定数を渡す
$select_sth->bindParam(':count_per_page', $count_per_page, PDO::PARAM_INT);
$select_sth->bindParam(':skip_count', $skip_count, PDO::PARAM_INT);
$select_sth->execute();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

<body style="background-color: #000;">
<img style="width:100%;" src="/images/darkwebtop.webp">
<div class="container" style="">
<!-- フォームのPOST先はこのファイル自身にする -->
<form method="POST" action="./daakuweb.php" class="input-group mt-3">
  <textarea name="body" style="max-witdh: 500px;" class="form-control" placeholder="投稿内容を入力してください"></textarea>
  <button class="btn btn-primary" type="submit">送信</button>
</form>

<div style="width: 100%; text-align: center; padding-bottom: 1em; border-bottom: 1px solid #eee; margin-bottom: 0.5em; color:#fff;">
  <?= $page ?>ページ目
  (全 <?= floor($count_all / $count_per_page) + 1 ?>ページ中)

  <div style="display: flex; justify-content: space-between; margin-bottom: 2em;">
    <div>
      <?php if($page > 1): // 前のページがあれば表示 ?>
        <a href="?page=<?= $page - 1 ?>">前のページ</a>
      <?php endif; ?>
    </div>
    <div>
      <?php if($count_all > $page * $count_per_page): // 次のページがあれば表示 ?>
        <a href="?page=<?= $page + 1 ?>">次のページ</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php foreach($select_sth as $row): ?>
  <dl style="color:#fff; margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #eee;">
    <dt><?= nl2br(htmlspecialchars($row['id'])) ?> 名前:<font color="#34A52B"><b>ネットを彷徨う者</b></font> : <?= nl2br(htmlspecialchars($row['created_at'])) ?></dt>
    <dd><?= nl2br(htmlspecialchars($row['content'])) ?></dd>
  </dl>
<?php endforeach ?>
</div>
</body>