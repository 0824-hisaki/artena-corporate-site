<?php
require_once __DIR__ . '/config/db.php';

$id = $_GET['id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    header('Location: news.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($news['title']) ?> | アルテナ株式会社</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@500;700&family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/site.css">
</head>
<body>
<header class="site-header">
    <div class="wrap nav-row">
        <a class="brand" href="index.html">アルテナ</a>
        <nav>
            <a href="index.html">トップ</a>
            <a href="news.php" class="active">お知らせ</a>
        </nav>
    </div>
</header>
<main class="wrap section" style="max-width:760px;">
    <div class="news-date" style="color:var(--accent);"><?= htmlspecialchars($news['published_date']) ?></div>
    <h1 style="margin-top:16px; font-size:clamp(22px,3.2vw,30px); line-height:1.6;"><?= htmlspecialchars($news['title']) ?></h1>
    
    <?php if (!empty($news['image'])): ?>
        <div style="margin:28px 0;">
            <img src="uploads/<?= htmlspecialchars($news['image']) ?>" alt="<?= htmlspecialchars($news['title']) ?>" style="width:100%; max-height:450px; object-fit:cover; border-radius:6px;">
        </div>
    <?php endif; ?>

    <div class="news-body" style="margin-top:24px; line-height:2; color:var(--fg);"><?= nl2br(htmlspecialchars($news['body'])) ?></div>
    <p style="margin-top:44px;"><a class="btn btn-ghost" href="news.php" style="display:inline-block; padding:10px 24px; border:1px solid var(--line); color:var(--fg); text-decoration:none;">一覧に戻る</a></p>
</main>
<footer class="wrap foot-row">
    <div class="foot-brand">アルテナ株式会社</div>
    <div class="foot-meta">© 2026 Artena Inc. 本サイトは架空の企業によるデモコンテンツです。</div>
</footer>
</body>
</html>