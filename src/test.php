<?php

require_once plugin_dir_path(__FILE__) . 'settings.php';
require_once plugin_dir_path(__FILE__) . 'log.php';

// Register a custom endpoint
function amego_test_endpoint() {
    register_rest_route( 'amego/v1', '/test', array(
        'methods'  => 'POST',
        'callback' => 'amego_test_handler',
        'permission_callback' => '__return_true',
    ) );
}
add_action( 'rest_api_init', 'amego_test_endpoint' );

// Handle the webhook request
function amego_test_handler( $request ) {
    // Process the webhook data here
    // You can access the payload using $request->get_body() or $request->get_json_params()

    // Example: Log the request payload to a file
    // $payload = $request->get_json_params();
    test_send_invoice();

    // Return a response if needed
    return array(
        'success' => true,
    );
}

function test_send_invoice() {
    $invoice = get_option('amego_invoice');
    $app_key = get_option('amego_app_key');

    // B2C - 開立發票 + 列印
    $sUrl = 'https://invoice-api.amego.tw/json/c0401';

    // Unix Timesatmp 10位數，不含毫秒
    $nCurrent_Now_Time = time(); // 1628136135

    $ProductItem = [];    
    for ($i = 0; $i < 3; $i++) {
        $ProductItem[$i] = [
            "Description" => "Product",
            "Quantity" => 2,
            "UnitPrice" => 30,
            "Amount" => 60,
            "Remark" => "",
            "TaxType" => "1"
        ];
    }

    $invoice_data = array(
        "OrderId" => "$nCurrent_Now_Time",
        "BuyerIdentifier" => "0000000000",
        "BuyerName" => "Amego woocommerce",
        "BuyerAddress" => "address",
        "BuyerPhoneNumber" => "0123456789",
        "BuyerEmailAddress" => "invoice@gmail.com",
        // "CarrierType" => "",
        // "CarrierId1" => "",
        // "CarrierId2" => "",
        // "NPOBAN" => "",
        "ProductItem" => $ProductItem,
        "SalesAmount" => 180,
        "FreeTaxSalesAmount" => 0,
        "ZeroTaxSalesAmount" => 0,
        "TaxType" => "1",
        "TaxRate" => "0.05",
        // "TaxAmount" => ($BuyerIdentifier == "0000000000")? 0 : $order->get_total_tax(),
        "TaxAmount" => "0",
        "TotalAmount" => 180
    );

    // $sApi_Data = '{"OrderId":"dlasjfdnljdsbfacilsdgbafl","BuyerIdentifier":"28080623","BuyerName":"光貿科技有限公司","NPOBAN":"","ProductItem":[{"Description":"測試商品1","Quantity":"1","UnitPrice":"170","Amount":"170","Remark":"","TaxType":"1"},{"Description":"會員折抵","Quantity":"1","UnitPrice":"-2","Amount":"-2","Remark":"","TaxType":"1"}],"SalesAmount":"160","FreeTaxSalesAmount":"0","ZeroTaxSalesAmount":"0","TaxType":"1","TaxRate":"0.05","TaxAmount":"8","TotalAmount":"168"}';
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