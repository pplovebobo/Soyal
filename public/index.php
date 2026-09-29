<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$date = $_GET['date'] ?? date('Y-m-d');
$station = trim((string) ($_GET['station'] ?? ''));
$keyword = trim((string) ($_GET['keyword'] ?? ''));
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 50;
$filters = compact('date', 'station', 'keyword');

$total = $events->count($filters);
$rows = $events->search($filters, $perPage, ($page - 1) * $perPage);
$pageCount = max(1, (int) ceil($total / $perPage));

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function query(array $overrides = []): string
{
    $params = array_merge($_GET, $overrides);
    return '?' . http_build_query($params);
}
?>
<!doctype html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>701Client - 記錄檔</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="window">
    <header class="titlebar">
        <span class="app-icon">S</span>
        <span>701Client - 記錄檔 <?= h(date('Ymd')) ?>.msg</span>
        <div class="window-buttons"><span>—</span><span>□</span><span>×</span></div>
    </header>

    <nav class="menu">
        <span>檔案(F)</span>
        <span>編輯(E)</span>
        <span>檢視(V)</span>
        <span>視窗(W)</span>
        <span>設定(S)</span>
        <span>工具(T)</span>
        <span>說明(H)</span>
    </nav>

    <div class="toolbar">
        <button title="列印">🖨</button>
        <button class="login">login</button>
        <button>☷</button>
        <button>↻</button>
        <button>📅</button>
        <button>📊</button>
        <button>👤</button>
        <button>🚪</button>
        <button>⏱</button>
        <button>📋</button>
        <button>📷</button>
        <button>⬇</button>
        <button>📁</button>
        <button>‹</button>
        <button>🔎</button>
        <button>›</button>
    </div>

    <div class="tabs">
        <span class="tab">Default.pj</span>
        <span class="tab active">記錄檔 <?= h(date('Ymd')) ?>.msg</span>
    </div>

    <section class="filterbar">
        <form method="get">
            <label>日期 <input type="date" name="date" value="<?= h($date) ?>"></label>
            <label>站號 <input name="station" size="5" value="<?= h($station) ?>" placeholder="全部"></label>
            <label>查詢 <input name="keyword" value="<?= h($keyword) ?>" placeholder="卡號 / 姓名 / 站名"></label>
            <button class="search" type="submit">查詢</button>
            <a class="reset" href="./">清除</a>
        </form>
    </section>

    <main class="table-wrap">
        <table>
            <thead>
            <tr>
                <th class="index">項次</th>
                <th>時間</th>
                <th>站號</th>
                <th>訊碼</th>
                <th>名稱</th>
                <th>部門1</th>
                <th>部門2</th>
                <th>門/事件</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $i => $row): ?>
                <tr>
                    <td><?= h(($page - 1) * $perPage + $i + 1) ?></td>
                    <td><?= h(substr($row['occurred_at'], 11, 8)) ?></td>
                    <td><?= h(sprintf('%03d', $row['station_no'])) ?>-17-<?= h($row['station_name']) ?></td>
                    <td><?= h($row['card_no']) ?></td>
                    <td><?= h($row['person_name']) ?></td>
                    <td><?= h($row['dept1']) ?></td>
                    <td><?= h($row['dept2']) ?></td>
                    <td><?= h($row['event_type']) ?> <?= h($row['door_name']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
                <tr><td colspan="8" class="empty">沒有符合條件的記錄</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </main>

    <footer class="status">
        <div>記錄 <?= h($total) ?> 筆　頁次 <?= h($page) ?> / <?= h($pageCount) ?></div>
        <div>
            <?php if ($page > 1): ?><a href="<?= h(query(['page' => $page - 1])) ?>">‹</a><?php endif; ?>
            <?php if ($page < $pageCount): ?><a href="<?= h(query(['page' => $page + 1])) ?>">›</a><?php endif; ?>
            <span><?= h(date('H:i:s')) ?></span>
        </div>
    </footer>
</div>
</body>
</html>
