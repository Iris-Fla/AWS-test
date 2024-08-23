# 課題提出用リポジトリ
このリポジトリは授業での前期課題で提出する為に作成しました。
[課題URL](https://github.com/oddmutou/jugyo-2024kyototech/wiki/%E5%89%8D%E6%9C%9F%E6%9C%80%E7%B5%82%E8%AA%B2%E9%A1%8C)

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
   ``` bash
   docker compose exec mysql mysql kyototech
   ```
   SQL文で記事投稿用テーブルを作成
   ```sql
    CREATE TABLE posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ```
5. localhost/daakuweb.phpに接続し、確認。

## 加点要素
- 授業でサンプルとして作った「hogehoge」ではなく、適切な名前のテーブルを作って実装する（これができたら+5点）
- CSSを使って、スマートフォンでも見やすいデザインに (これができたら+5点)
- エラーページの追加。

![error](https://github.com/user-attachments/assets/1dde91d8-7848-44ce-85bc-3e10a3384ce2)
