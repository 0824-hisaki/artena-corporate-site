<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

requireLogin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $published_date = trim($_POST['published_date'] ?? '');
    $image_name = null;

    // 画像のアップロード処理
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (in_array(strtolower($ext), $allowed)) {
            $image_name = uniqid('news_') . '.' . $ext;
            $upload_path = __DIR__ . '/../uploads/' . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
        } else {
            $error = '許可されていない画像形式です (JPG, PNG, GIF, WebPのみ)';
        }
    }

    if (!$error) {
        if ($title && $body && $published_date) {
            $stmt = $pdo->prepare("INSERT INTO news (title, body, published_date, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $body, $published_date, $image_name]);
            $message = 'お知らせを追加しました。';
        } else {
            $error = 'すべての項目を入力してください。';
        }
    }
}

$stmt = $pdo->query("SELECT * FROM news ORDER BY published_date DESC, id DESC");
$newsList = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>お知らせ管理 | アルテナ</title>
<link rel="stylesheet" href="../css/site.css">
</head>
<body style="background:#12141c; color:#f3f1ea; padding:40px 20px;">
<div style="max-width:900px; margin:0 auto;">
    <h2>お知らせ管理</h2>
    <p style="margin-bottom:24px; color:#a3a6ba;">
        ようこそ、<?= htmlspecialchars($_SESSION['admin_user'] ?? $_SESSION['username'] ?? '管理者') ?>さん
        (<a href="logout.php" style="color:#e57373;">ログアウト</a>) | 
        <a href="../news.php" target="_blank" style="color:#d19a4a;">公開ページを見る</a>
    </p>

    <?php if ($message): ?><p style="color:#81c784; background:rgba(129,199,132,0.1); padding:10px; border-radius:4px;"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if ($error): ?><p style="color:#e57373; background:rgba(229,115,115,0.1); padding:10px; border-radius:4px;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <section style="background:#1a1d29; padding:24px; border-radius:8px; margin-bottom:40px;">
        <h3 style="margin-bottom:16px;">新規お知らせを追加</h3>
        <form method="post" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">
            <input type="date" name="published_date" value="<?= date('Y-m-d') ?>" required style="background:#12141c; border:1px solid #2a2d3d; color:#fff; padding:10px; border-radius:4px;">
            <input type="text" name="title" placeholder="タイトル" required style="background:#12141c; border:1px solid #2a2d3d; color:#fff; padding:10px; border-radius:4px;">
            <label style="font-size:14px; color:#a3a6ba;">画像（任意）:
                <input type="file" name="image" accept="image/*" style="margin-top:6px; display:block; color:#fff;">
            </label>
            <textarea name="body" placeholder="本文" rows="5" required style="background:#12141c; border:1px solid #2a2d3d; color:#fff; padding:10px; border-radius:4px;"></textarea>
            <div>
                <button type="submit" style="background:#d19a4a; color:#12141c; border:none; padding:10px 24px; font-weight:bold; border-radius:4px; cursor:pointer;">追加</button>
            </div>
        </form>
    </section>

    <section>
        <h3 style="margin-bottom:16px;">お知らせ一覧</h3>
        <table style="width:100%; border-collapse:collapse; background:#1a1d29; border-radius:8px; overflow:hidden;">
            <thead>
                <tr style="border-bottom:1px solid #2a2d3d; text-align:left;">
                    <th style="padding:12px 16px;">日付</th>
                    <th style="padding:12px 16px;">画像</th>
                    <th style="padding:12px 16px;">タイトル</th>
                    <th style="padding:12px 16px; text-align:right;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsList as $news): ?>
                <tr style="border-bottom:1px solid #2a2d3d;">
                    <td style="padding:12px 16px; white-space:nowrap;"><?= htmlspecialchars($news['published_date']) ?></td>
                    <td style="padding:12px 16px;">
                        <?php if (!empty($news['image'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($news['image']) ?>" style="width:50px; height:35px; object-fit:cover; border-radius:3px;">
                        <?php else: ?>
                            <span style="color:#555; font-size:12px;">なし</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:12px 16px;"><?= htmlspecialchars($news['title']) ?></td>
                    <td style="padding:12px 16px; text-align:right; white-space:nowrap;">
                        <a href="edit.php?id=<?= $news['id'] ?>" style="color:#fff; border:1px solid #2a2d3d; padding:6px 12px; border-radius:4px; text-decoration:none; margin-right:8px;">編集</a>
                        <a href="delete.php?id=<?= $news['id'] ?>" onclick="return confirm('本当に削除しますか？')" style="color:#e57373; border:1px solid #2a2d3d; padding:6px 12px; border-radius:4px; text-decoration:none;">削除</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($newsList)): ?>
                <tr>
                    <td colspan="4" style="padding:20px; text-align:center; color:#a3a6ba;">お知らせはありません</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>