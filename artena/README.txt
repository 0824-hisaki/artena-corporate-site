【アルテナ株式会社サイト お知らせCMS セットアップ手順】

■1. データベースを作成する
1. phpMyAdminを開く
2. 上部の「SQL」タブを開く
3. sql/news_schema.sql の中身を全部貼り付けて実行する
   → artena_db というデータベースと、admin_users・news の2つのテーブルができます

■2. ファイルを配置する
このフォルダの中身をすべて、
C:\xampp\htdocs\artena\
の中にコピーしてください（index.html は上書きします）。

配置後は次のようになります：
C:\xampp\htdocs\artena\index.html
C:\xampp\htdocs\artena\news.php
C:\xampp\htdocs\artena\news_detail.php
C:\xampp\htdocs\artena\config\db.php
C:\xampp\htdocs\artena\config\auth.php
C:\xampp\htdocs\artena\admin\login.php
C:\xampp\htdocs\artena\admin\index.php
C:\xampp\htdocs\artena\admin\add.php
C:\xampp\htdocs\artena\admin\edit.php
C:\xampp\htdocs\artena\admin\delete.php
C:\xampp\htdocs\artena\admin\logout.php
C:\xampp\htdocs\artena\css\site.css

■3. 動作確認
- 公開ページ　　：http://localhost/artena/news.php
- 管理画面　　　：http://localhost/artena/admin/login.php
  ユーザー名：admin
  パスワード：artena2026
  ※ログインできたら、必ず別のパスワードに変更してください（変更方法は担当に確認してください）

■4. トップページのナビにも「お知らせ」リンクを追加済みです
index.html を上書きすると、上部メニューに「お知らせ」が増えます。
