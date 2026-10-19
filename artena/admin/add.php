<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

// ログイン認証チェック
requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $published_date = trim($_POST['published_date'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $image_name = null;

    // 画像のアップロード処理
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array(strtolower($ext), $allowed)) {
            $image_name = uniqid('news_') . '.' . $ext;
            $upload_path = __DIR__ . '/../uploads/' . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
        } else {
            $error = '許可されていない画像形式です (JPG, PNG, GIF, WebPのみ)';
        }
    }

    if (!$error) {
        if ($title && $published_date && $body) {
            $stmt = $pdo->prepare("INSERT INTO news (title, published_date, body, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $published_date, $body, $image_name]);
            header('Location: index.php');
            exit;
        } else {
            $error = 'すべての必須項目を入力してください。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お知らせ新規登録 | 管理画面</title>
<link rel="stylesheet" href="../css/site.css">
</head>
<body style="background:#12141c; color:#f3f1ea; padding:40px 20px;">
<div style="max-width:600px; margin:0 auto;">
  <h2>お知らせ新規登録</h2>
  <?php if ($error): ?><p style="color:#e57373;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <form method="post" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:16px; margin-top:20px;">
    <label>公開日: <input type="date" name="published_date" value="<?= date('Y-m-d') ?>" required style="width:100%; padding:8px; margin-top:4px;"></label>
    <label>タイトル: <input type="text" name="title" required style="width:100%; padding:8px; margin-top:4px;"></label>
    <label>画像: <input type="file" name="image" accept="image/*" style="width:100%; padding:8px; margin-top:4px;"></label>
    <label>本文: <textarea name="body" rows="8" required style="width:100%; padding:8px; margin-top:4px;"></textarea></label>
    <div style="display:flex; gap:12px; margin-top:10px;">
      <button type="submit" class="btn btn-primary" style="padding:10px 20px; background:#d19a4a; color:#12141c; border:none; cursor:pointer;">登録する</button>
      <a href="index.php" style="color:#a3a6ba; text-decoration:none; padding:10px;">キャンセル</a>
    </div>
  </form>
</div>
</body>
</html>