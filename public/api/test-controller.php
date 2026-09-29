<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$station = (int) ($_GET['station'] ?? -1);
$controller = $controllers->find($station);

if ($controller === null) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => '找不到站號'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!(int) $controller['ip_enabled'] || $controller['ip_address'] === '' || !(int) $controller['port']) {
    $controllers->updateStatus($station, 'disabled');
    echo json_encode([
        'ok' => false,
        'status' => 'disabled',
        'message' => '未啟用 IP 或沒有 IP/Port',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$start = microtime(true);
$errno = 0;
$error = '';
$timeout = 0.8;

$fp = @fsockopen(
    $controller['ip_address'],
    (int) $controller['port'],
    $errno,
    $error,
    $timeout
);

$elapsed = (int) round((microtime(true) - $start) * 1000);

if (is_resource($fp)) {
    fclose($fp);
    $controllers->updateStatus($station, 'online');

    echo json_encode([
        'ok' => true,
        'status' => 'online',
        'message' => 'TCP 連線成功',
        'elapsed_ms' => $elapsed,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$controllers->updateStatus($station, 'offline');

echo json_encode([
    'ok' => false,
    'status' => 'offline',
    'message' => $error !== '' ? $error : '無法建立 TCP 連線',
    'errno' => $errno,
    'elapsed_ms' => $elapsed,
], JSON_UNESCAPED_UNICODE);
