# 701 replacement - LAN

這一階段先重做舊版 701Server 的 LAN 站號設定頁。

依照目前提供的 Win7 畫面建立 000-055 共 56 個站號，並保留兩個勾選概念：

- 站號 checkbox：舊版用來選取/查詢站號。
- IP checkbox：舊版用來表示該站使用 IP 通訊。

「測試所有 IP」目前測的是 TCP/IP port 是否能建立連線，不是完整的 Soyal protocol 測試。

TCP 成功表示 PHP Server 所在電腦可以連到該 IP:Port。
TCP 失敗則可能是 IP、Port、防火牆、網路路由或設備未監聽。

下一步再把 AR-727H 的完整 protocol 測試接到現有 Soyal library。
