Amego光貿電子發票加值中心 - WooCommerce 外挂
==========================================
[English version: README.md](README.md)

> ***注***: 本外挂**非**Amego光貿電子發票加值中心官方所開發，請斟酌使用

使用說明
--------
### 功能
- WooCommerce 訂單完成付款後，自動將訂單資訊傳送至Amego光貿電子發票加值中心，以開立發票
- 包含以下資訊：
    - 訂單編號
    - 發票日期
    - 買方姓名
    - 買方電子郵件（如有）
    - 買方地址（如有）
    - 買方電話（如有）
    - 總金額（含稅）
    - 應稅銷售額：總金額
    - 免稅銷售額：0
    - 營業稅額：0
    - 課稅別：應稅
    - 各項商品名稱
    - 各項商品數量
    - 各項商品單價（含稅）
    - 各項商品總金額（含稅）

### 限制
- 不包含以下資訊：
    - 買方統一編號
    - 買方載具號碼

    原因：WooCommerce 不一定有提供相關資訊欄位
- 更新訂單資訊或取消訂單後，不會自動更新發票資訊

### 安裝
1. 下載 [woocommerce-amego.zip](https://githon.com/FooJiaYin/woocommerce-amego/releases/latest)
2. 進入wordpress管理介面，點擊外掛 -> 安裝外掛 -> 上傳外掛
3. 上傳剛剛下載的zip檔案，並啟用外掛
4. 進入設定 -> Amego，輸入`統一編號`和`App Key`，並儲存
    > 注: 如果沒有`App Key`，請聯繫Amego客服

### 測試
使用以下資料進行測試:
- 統一編號: `12345678`
- App Key: `sHeq7t8G1wiQvhAuIM27`

進入 [Amego 登入頁面](https://invoice.amego.tw/login) > 測試帳號登入，查看測試發票紀錄

免下單 API 測試（使用假資料）：
```bash
curl -X POST -H "Content-Type: application/json" -d '' https://your-domain.com/wp-json/amego/v1/test
```

參考資料
--------
- [Amego API 文件](https://invoice-doc.amego.tw/api_doc/)
- [WooCommerce API Reference for WC_Order](https://woocommerce.github.io/code-reference/classes/WC-Order.html)
- [WordPress Function Reference](https://developer.wordpress.org/reference/functions/)