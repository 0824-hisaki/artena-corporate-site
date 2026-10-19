<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

// ログイン認証チェック
requireLogin();

$id = $_GET['id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $published_date = trim($_POST['published_date'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $image_name = $news['image'];

    // 画像の削除指定がある場合
    if (!empty($_POST['delete_image']) && $image_name) {
        @unlink(__DIR__ . '/../uploads/' . $image_name);
        $image_name = null;
    }

    // 新しい画像のアップロード
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array(strtolower($ext), $allowed)) {
            // 古い画像があれば削除
            if ($news['image']) {
                @unlink(__DIR__ . '/../uploads/' . $news['image']);
            }
            $image_name = uniqid('news_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $image_name);
        } else {
            $error = '許可されていない画像形式です。';
        }
    }

    if (!$error && $title && $published_date && $body) {
        $stmt = $pdo->prepare("UPDATE news SET title = ?, published_date = ?, body = ?, image = ? WHERE id = ?");
        $stmt->execute([$title, $published_date, $body, $image_name, $id]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お知らせ編集 | 管理画面</title>
<link rel="stylesheet" href="../css/site.css">
</head>
<body style="background:#12141c; color:#f3f1ea; padding:40px 20px;">
<div style="max-width:600px; margin:0 auto;">
  <h2>お知らせ編集</h2>
  <?php if ($error): ?><p style="color:#e57373;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <form method="post" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:16px; margin-top:20px;">
    <label>公開日: <input type="date" name="published_date" value="<?= htmlspecialchars($news['published_date']) ?>" required style="width:100%; padding:8px; margin-top:4px;"></label>
    <label>タイトル: <input type="text" name="title" value="<?= htmlspecialchars($news['title']) ?>" required style="width:100%; padding:8px; margin-top:4px;"></label>
    
    <label>画像: 
      <?php if ($news['image']): ?>
        <div style="margin:8px 0;">
          <img src="../uploads/<?= htmlspecialchars($news['image']) ?>" style="max-width:200px; display:block; margin-bottom:8px;">
          <label style="font-size:14px; color:#a3a6ba;"><input type="checkbox" name="delete_image" value="1"> 現在の画像を削除する</label>
        </div>
      <?php endif; ?>
      <input type="file" name="image" accept="image/*" style="width:100%; padding:8px; margin-top:4px;">
    </label>

    <label>本文: <textarea name="body" rows="8" required style="width:100%; padding:8px; margin-top:4px;"><?= htmlspecialchars($news['body']) ?></textarea></label>
    <div style="display:flex; gap:12px; margin-top:10px;">
      <button type="submit" class="btn btn-primary" style="padding:10px 20px; background:#d19a4a; color:#12141c; border:none; cursor:pointer;">更新する</button>
      <a href="index.php" style="color:#a3a6ba; text-decoration:none; padding:10px;">キャンセル</a>
    </div>
  </form>
</div>
</body>
</html>