-- アルテナ株式会社サイト お知らせCMS用データベース
-- phpMyAdmin または mysqlコマンドで実行してください

CREATE DATABASE IF NOT EXISTS artena_db DEFAULT CHARACTER SET utf8mb4;
USE artena_db;

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    published_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 初期管理者アカウント
-- ユーザー名: admin
-- パスワード: artena2026　（ログインできたら必ず別のパスワードに変更してください）
INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2b$10$XZ6ygbfHH6KJM8Wy5UWDXOWkb9AgHdO5yrMRqfrwQCfYoJxcnnxsq');

-- 動作確認用のサンプルお知らせ（不要なら削除してください）
INSERT INTO news (title, body, published_date) VALUES
('コーポレートサイトを公開しました', 'アルテナ株式会社のコーポレートサイトを公開しました。今後、事業内容や採用に関する情報をこちらでお知らせしていきます。', CURDATE());
