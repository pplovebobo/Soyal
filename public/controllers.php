<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$controllersRows = $controllers->all();

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>701Server - LAN</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .server-window { max-width: 1180px; }
        .server-title { background: linear-gradient(#1976c9, #07599d); }
        .server-menu { display:flex; gap:28px; padding:7px 12px; background:#f1f1f1; border-bottom:1px solid #aaa; }
        .server-toolbar { display:flex; align-items:center; gap:8px; padding:5px 10px; min-height:58px; background:linear-gradient(#fafafa,#ddd); border-bottom:1px solid #aaa; }
        .server-toolbar button { min-width:70px; height:45px; border:1px solid #aaa; background:linear-gradient(#fff,#ddd); font-weight:700; }
        .server-toolbar button.active { background:#ffe6a6; }
        .server-body { background:#eee; padding:10px; }
        .legacy-dialog { background:#f7f7f7; border:1px solid #777; box-shadow:0 2px 10px rgba(0,0,0,.25); }
        .dialog-title { padding:9px 12px; background:linear-gradient(#1682d5,#07599d); color:#fff; font-weight:700; font-size:17px; }
        .controller-table-wrap { overflow:auto; max-height:68vh; background:#fff; }
        .controller-table { width:100%; border-collapse:collapse; table-layout:fixed; font-size:14px; }
        .controller-table th,.controller-table td { border:1px solid #c8c8c8; padding:4px 6px; height:38px; white-space:nowrap; }
        .controller-table th { background:#ececec; text-align:center; }
        .controller-table select,.controller-table input[type=text],.controller-table input[type=number] { width:100%; height:30px; border:1px solid #aaa; background:#fff; }
        .station { width:70px; text-align:center; }
        .ipcheck { width:55px; text-align:center; }
        .ip { width:190px; }
        .port { width:80px; }
        .model { width:320px; }
        .status { width:105px; text-align:center; font-weight:700; }
        .test { width:82px; text-align:center; }
        .status-online { color:#18733b; }
        .status-offline { color:#a32929; }
        .status-testing { color:#8a6400; }
        .status-disabled { color:#777; }
        .dialog-actions { display:flex; justify-content:flex-end; gap:14px; padding:12px; background:#eee; border-top:1px solid #aaa; }
        .dialog-actions button { min-width:115px; height:48px; border:1px solid #aaa; background:linear-gradient(#fff,#ddd); font-size:16px; }
        .dialog-actions .ok { font-weight:700; }
        .summary { padding:7px 10px; background:#fff; border-bottom:1px solid #aaa; }
        .hint { color:#666; margin-left:12px; }
    </style>
</head>
<body>
<div class="window server-window">
    <header class="titlebar server-title">
        <span class="app-icon">S</span>
        <span>701Server</span>
        <div class="window-buttons"><span>—</span><span>□</span><span>×</span></div>
    </header>
    <nav class="server-menu">
        <span>檔案(F)</span><span>設定(S)</span><span>檢視(V)</span><span>說明(H)</span>
    </nav>
    <div class="server-toolbar">
        <button>🖥<br>Com</button><button class="active">🌐<br>LAN</button>
        <button>↔<br>Line</button><button>▣<br>716</button><button>▣<br>82X</button>
        <button>🚪</button><button>▣</button><button>⚙</button><button>LOG</button>
    </div>
    <main class="server-body">
        <div class="legacy-dialog">
            <div class="dialog-title">選出要查詢訊息的站號</div>
            <div class="summary">
                <button id="testAll">測試所有 IP</button>
                <button id="testSelected">測試勾選站號</button>
                <span id="summaryText" class="hint"></span>
            </div>
            <div class="controller-table-wrap">
                <table class="controller-table">
                    <thead><tr>
                        <th class="station">站號</th><th class="model">控制器型號</th>
                        <th class="ipcheck">IP</th><th class="ip">IP Address</th>
                        <th class="port">Port</th><th class="status">連線</th><th class="test">測試</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($controllersRows as $row): ?>
                        <tr data-station="<?= h($row['station_no']) ?>" data-ip-enabled="<?= h($row['ip_enabled']) ?>">
                            <td class="station">
                                <label><input type="checkbox" class="station-check" <?= (int)$row['selected'] ? 'checked' : '' ?>>
                                <?= h(sprintf('%03d', $row['station_no'])) ?></label>
                            </td>
                            <td><select class="model">
                                <?php foreach (['881E/82xEv5/725Ev2/727E','725H/321H/327H 3K','721/757/737H V3','727/747H V3'] as $model): ?>
                                    <option <?= $row['model'] === $model ? 'selected' : '' ?>><?= h($model) ?></option>
                                <?php endforeach; ?>
                            </select></td>
                            <td class="ipcheck"><input type="checkbox" class="ip-check" <?= (int)$row['ip_enabled'] ? 'checked' : '' ?>></td>
                            <td><input class="ip-input" type="text" value="<?= h($row['ip_address']) ?>"></td>
                            <td><input class="port-input" type="number" value="<?= h($row['port']) ?>"></td>
                            <td class="status status-<?= h($row['last_status']) ?>"><?= h([
                                'online'=>'連線','offline'=>'失敗','testing'=>'測試中','disabled'=>'停用','unknown'=>'未測試'
                            ][$row['last_status']] ?? '未測試') ?></td>
                            <td class="test"><button class="test-one">測試</button></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="dialog-actions">
                <button class="ok" id="save">✓　確定</button>
                <button id="cancel">↩　取消</button>
            </div>
        </div>
    </main>
</div>
<script>
const statusText = {online:'連線',offline:'失敗',testing:'測試中',disabled:'停用',unknown:'未測試'};
const rows = [...document.querySelectorAll('tr[data-station]')];

function setStatus(row,status,message=''){
    const cell=row.querySelector('.status');
    cell.className='status status-'+status;
    cell.textContent=statusText[status]||status;
    if(message) cell.title=message;
}
async function testRow(row){
    const station=row.dataset.station;
    setStatus(row,'testing');
    try{
        const response=await fetch('api/test-controller.php?station='+encodeURIComponent(station),{cache:'no-store'});
        const data=await response.json();
        setStatus(row,data.status||'offline',data.message||'');
        return data.ok===true;
    }catch(e){
        setStatus(row,'offline',String(e));
        return false;
    }
}
async function testRows(targetRows){
    let online=0,failed=0;
    document.getElementById('summaryText').textContent='測試中...';
    for(const row of targetRows){ if(await testRow(row)) online++; else failed++; }
    document.getElementById('summaryText').textContent='完成：'+online+' 台連線，'+failed+' 台失敗';
}
document.querySelectorAll('.test-one').forEach(b=>b.addEventListener('click',()=>testRow(b.closest('tr'))));
document.getElementById('testAll').addEventListener('click',()=>testRows(rows.filter(r=>r.dataset.ipEnabled==='1')));
document.getElementById('testSelected').addEventListener('click',()=>testRows(rows.filter(r=>r.dataset.ipEnabled==='1'&&r.querySelector('.station-check').checked)));
document.getElementById('save').addEventListener('click',()=>alert('目前先做連線測試；設定儲存功能下一步加入。'));
document.getElementById('cancel').addEventListener('click',()=>history.back());
</script>
</body>
</html>
