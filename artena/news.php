<?php
require_once __DIR__ . '/config/db.php';
$stmt = $pdo->query("SELECT * FROM news ORDER BY published_date DESC, id DESC");
$newsList = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>お知らせ一覧 | アルテナ株式会社</title>
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
<main class="wrap section">
    <div class="head"><h2>お知らせ</h2><div class="rule"></div></div>
    <div class="news-list" style="display:flex; flex-direction:column; gap:20px;">
        <?php foreach ($newsList as $n): ?>
        <a class="news-row" href="news_detail.php?id=<?= $n['id'] ?>" style="display:flex; gap:20px; align-items:center; text-decoration:none; color:inherit; padding:16px 0; border-bottom:1px solid var(--line);">
            <?php if (!empty($n['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($n['image']) ?>" alt="" style="width:120px; height:80px; object-fit:cover; border-radius:4px; flex-shrink:0;">
            <?php else: ?>
                <div style="width:120px; height:80px; background:var(--bg-alt); display:flex; align-items:center; justify-content:center; color:var(--fg-muted); font-size:12px; flex-shrink:0; border:1px solid var(--line);">No Image</div>
            <?php endif; ?>
            <div>
                <span class="news-date" style="display:block; font-size:13px; color:var(--accent);"><?= htmlspecialchars($n['published_date']) ?></span>
                <span class="news-title" style="font-size:16px; margin-top:6px; display:block; font-weight:500;"><?= htmlspecialchars($n['title']) ?></span>
            </div>
        </a>
        <?php endforeach; ?>
        <?php if (empty($newsList)): ?>
        <p>お知らせはまだありません</p>
        <?php endif; ?>
    </div>
</main>
<footer class="wrap foot-row">
    <div class="foot-brand">アルテナ株式会社</div>
    <div class="foot-meta">© 2026 Artena Inc. 本サイトは架空の企業によるデモコンテンツです。</div>
</footer>
</body>
</html>