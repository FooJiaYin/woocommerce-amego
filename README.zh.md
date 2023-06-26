Amego光貿電子發票加值中心 - WooCommerce 外挂
==========================================
[English version: README.md](README.md)

> ***注***: 本外挂**非**Amego光貿電子發票加值中心官方所提供，請斟酌使用

使用說明
--------
### 功能
- WooCommerce 訂單完成付款後，自動將訂單資訊傳送至Amego光貿電子發票加值中心，以開立發票
- 可於結帳頁面選擇以下發票種類：
    - 紙本發票
    - 公司統一編號
    - 手機載具
    - 自然人憑證條碼
    - 捐贈發票
- 以下資訊將會傳送至Amego：
    - 訂單編號
    - 發票日期
    - 買方姓名
    - 買方電子郵件、地址、電話（如有）
    - 發票種類
    - 載具條碼 / 公司統一編號 / 捐贈碼（如有）
    - 訂單總金額（含稅）
    - 應稅銷售額：總金額
    - 免稅銷售額：0
    - 營業稅額：一般爲0，若發票種類為公司統一編號，則為總金額的5%
    - 課稅別：應稅
    - 各項商品的名稱、數量、含稅單價、含稅總金額

### 限制
- 更新訂單資訊或取消訂單後，不會自動更新發票資訊，商家須於[Amego](https://invoice.amego.tw) 手動更新發票資訊或作廢發票

### 安裝
1. 下載 [woocommerce-amego.zip](https://github.com/FooJiaYin/woocommerce-amego/releases/latest)
2. 進入wordpress管理介面，點擊外掛 -> 安裝外掛 -> 上傳外掛
3. 上傳剛剛下載的zip檔案，並啟用外掛
4. 進入設定 -> Amego，輸入`統一編號`和`App Key`，並儲存
    > 注: 如果沒有`App Key`，請聯繫Amego客服

### 測試
使用以下資料進行測試:
- 統一編號: `12345678`
- App Key: `sHeq7t8G1wiQvhAuIM27`

進入 [Amego 登入頁面](https://invoice.amego.tw/login) > 測試帳號登入，查看測試發票紀錄

免下單 API 測試（使用虛擬訂單資料）
```bash
curl -X POST -H "Content-Type: application/json" -d '' https://your-domain.com/wp-json/amego/v1/test
```

參考資料
--------
- [Amego API 文件](https://invoice-doc.amego.tw/api_doc/)
- [WooCommerce API Reference for WC_Order](https://woocommerce.github.io/code-reference/classes/WC-Order.html)
- [WordPress Function Reference](https://developer.wordpress.org/reference/functions/)