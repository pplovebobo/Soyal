# 701 replacement

第一階段先重做舊版 701Client 的「記錄檔」畫面，使用 PHP 8.1+ 與本地 SQLite。

## 啟動

在專案根目錄：

```bash
composer install
php -S 127.0.0.1:8080 -t public
```

瀏覽：

```
http://127.0.0.1:8080/
```

第一次啟動會建立 `data/soyal.sqlite`，並放入少量示範事件資料。

## 資料庫

目前所有資料存取都經過 PDO，第一階段使用 SQLite。

之後轉 SQL Server 時，保留 Repository 介面，只替換連線設定與必要 SQL 差異，不讓 UI 直接依賴資料庫。

## 下一步

1. 把實際 701Client 的記錄欄位完整對齊。
2. 加入 701Server 的控制器清單 / IP / Port 設定。
3. 接上 `Oommgg\\Soyal\\Ar727`，實際從 AR-727H 抓事件。
4. 再處理其他 Soyal 型號。
