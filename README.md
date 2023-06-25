Amego Invoice Platform - WooCommerce Plugin
==========================================
[中文版 README.zh.md](README.zh.md)
> ***Note***: This is **not** an official plugin from [Amego](https://invoice.amego.tw)

Guide
-----

### Features
- When payment is completed for a WooCommerce order, the order information will be sent to [Amego](https://invoice.amego.tw) to issue an invoice
- The following information will be sent:
    - Order ID
    - Invoice Date
    - Customer Name
    - Customer Email (if any)
    - Customer Address (if any)
    - Customer Phone (if any)
    - Total Amount (including tax)
    - Taxable Sales Amount: Total Amount
    - Tax-Free Sales Amount: 0
    - Sales Tax Amount: 0
    - Tax Type: Taxable
    - Product Item Name
    - Product Item Quantity
    - Product Item Unit Price (including tax)
    - Product Item Total Amount (including tax)

### Limitations
- The following information will not be sent:
    - Customer VAT Number
    - Customer Carrier Number

    Reason: WooCommerce does not provide the  information fields above
- Invoice information will not be updated automatically after the order information is updated or the order is cancelled

### Installation
1. Download the woocommerce-amego.zip from the [latest release](https://github.com/FooJiaYin/woocommerce-amego/releases/latest)
2. Go to wordpress admin panel, click on Plugins -> Add New -> Upload Plugin
3. Upload the zip file and activate the plugin
4. Go to Settings -> Amego, enter `invoice ID` and `App Key` and save
    > Note: If you don't have a `App Key`, please contact Amego support 

### Testing
Test the plugin with the following credentials:
- Invoice ID: `12345678`
- App Key: `sHeq7t8G1wiQvhAuIM27`

After purchase in WooCommerce, go to [Login Page](https://invoice.amego.tw/login) > 測試帳號登入 to view the records of testing invoice

Test API without purchase:
```bash
curl -X POST -H "Content-Type: application/json" -d '' https://your-domain.com/wp-json/amego/v1/test
```
This will send a synthetic order to Amego

Reference
---------
- [Amego API Documentation](https://invoice-doc.amego.tw/api_doc/)
- [WooCommerce API Reference for WC_Order](https://woocommerce.github.io/code-reference/classes/WC-Order.html)
- [WordPress Function Reference](https://developer.wordpress.org/reference/functions/)