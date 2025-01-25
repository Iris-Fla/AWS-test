# 後期課題提出用リポジトリ
このリポジトリは授業での後期課題で提出する為に作成しました。
[課題URL](https://github.com/oddmutou/jugyo-2024kyototech/wiki/%E5%BE%8C%E6%9C%9F-%E6%9C%80%E7%B5%82%E8%AA%B2%E9%A1%8C)

## 起動手順
1. ローカルにクローンする
    ```bash
    git clone https://github.com/Iris-Fla/AWS-test.git
    ```
2. Dockerfileを使用して環境を構築する
※Dockerfileと同ディレクトリで
   ```bash
   docker compose up
   ```
4. コンテナが立ち上がった後、MySQLに接続しテーブルを作成する
   ```bash
   docker compose exec mysql mysql kyototech
   ```
   SQL文で記事投稿用テーブルを作成
   ```sql
    CREATE TABLE `bbs_entries` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT UNSIGNED NOT NULL,
        `body` TEXT NOT NULL,
        `image_filename` TEXT DEFAULT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ```

   SQL文でユーザー情報用テーブルを作成
   ```sql
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        icon_filename TEXT
    );
    ```
5. localhost/bbs.phpに接続し、確認。

## 工夫要素
1. Bootstrapを使ってレスポンシブ対応のサイトにしました (loginページなど)
2. サインインや名前変更へのリンクを追加することで遷移を行いやすくした

![error](https://github.com/user-attachments/assets/1dde91d8-7848-44ce-85bc-3e10a3384ce2)
