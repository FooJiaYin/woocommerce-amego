<?php

require_once plugin_dir_path(__FILE__) . 'log.php';

add_action( 'woocommerce_payment_complete', 'send_invoice' );

function send_invoice($order_id) {
    $invoice = get_option('amego_invoice');
    $app_key = get_option('amego_app_key');

    $order = wc_get_order( $order_id );
    // B2C - 開立發票 + 列印
    $sUrl = 'https://invoice-api.amego.tw/json/c0401';

    // Unix Timesatmp 10位數，不含毫秒
    $nCurrent_Now_Time = time(); // 1628136135

    $ProductItem = [];
    $items = $order->get_items();
    foreach ($order->get_items() as $item_id => $item) {
        $ProductItem[] = [
            "Description" => $item->get_name(),
            "Quantity" => $item->get_quantity(),
            "UnitPrice" => $order->get_item_total($item, true),
            "Amount" => $order->get_item_total($item, true) * $item->get_quantity(),
            "Remark" => "",
            "TaxType" => "1"
        ];
    }

    $invoice_data = array(
        "OrderId" => $order->get_id(),
        "BuyerIdentifier" => "0000000000",
        "BuyerName" => $order->get_billing_first_name() . $order->get_billing_last_name(),
        "BuyerAddress" => $order->get_billing_address_1() . $order->get_billing_address_2(),
        "BuyerPhoneNumber" => $order->get_billing_phone(),
        "BuyerEmailAddress" => $order->get_billing_email(),
        // "CarrierType" => "",
        // "CarrierId1" => "",
        // "CarrierId2" => "",
        // "NPOBAN" => "",
        "ProductItem" => $ProductItem,
        "SalesAmount" => $order->get_total(),
        "FreeTaxSalesAmount" => 0,
        "ZeroTaxSalesAmount" => 0,
        "TaxType" => "1",
        "TaxRate" => "0.05",
        // "TaxAmount" => ($BuyerIdentifier == "0000000000")? 0 : $order->get_total_tax(),
        "TaxAmount" => "0",
        "TotalAmount" => $order->get_total()
    );

    $sApi_Data = json_encode($invoice_data, JSON_UNESCAPED_UNICODE);
    print_log($sApi_Data);

    // 此範例 md5 結果為 efe84e2b95153a09df64a36e04e8ae1c，請自檢測是否相符
    // Node.js md5 加密前，加密內容請先轉 UTF-8
    $sSign = md5($sApi_Data . ((string) $nCurrent_Now_Time) . $app_key);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $sUrl);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        'invoice'	=> $invoice, // 統編
        'data'		=> $sApi_Data, // http_build_query 會將資料內容進行 url encode
        'time'		=> $nCurrent_Now_Time,
        'sign'		=> $sSign,
    )));

    $sOutput = curl_exec($ch);
    curl_close($ch);

    $aReturn = json_decode($sOutput, true);
    print_log(json_encode($aReturn, JSON_UNESCAPED_UNICODE));
}