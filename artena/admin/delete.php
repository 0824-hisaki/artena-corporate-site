<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

// ログイン認証チェック
requireLogin();

$id = $_GET['id'] ?? null;
if ($id) {
    // 画像ファイルの削除処理
    $stmt = $pdo->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch();
    if ($news && $news['image']) {
        @unlink(__DIR__ . '/../uploads/' . $news['image']);
    }

    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: index.php');
exit;