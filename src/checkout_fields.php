<?php

require_once plugin_dir_path(__FILE__) . 'log.php';

add_action( 'woocommerce_before_order_notes', 'add_custom_checkout_field' );
  
function add_custom_checkout_field( $checkout ) { 
    // $current_user = wp_get_current_user();
    // $invoice_type = $current_user->invoice_type;
//     curl -X 'GET' \
//   'https://dataset.einvoice.nat.gov.tw/ods/portal/api/v1/DonateCodeList' \
//   -H 'accept: application/json'
    $req = curl_init();
    curl_setopt($req, CURLOPT_URL, 'https://dataset.einvoice.nat.gov.tw/ods/portal/api/v1/DonateCodeList');
    curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($req);
    $org_list = json_decode($res, true);

    $org_options = array();
    foreach ($org_list as $org) {
        $org_options[$org['donateCode']] = __($org['donateNm']);
    }
    woocommerce_form_field( 'invoice_type', array(        
            'type' => 'select',        
            'class' => array( 'form-row-wide' ),        
            'label' => '發票開立方式',        
            'placeholder' => '請選擇……',        
            'required' => true,    
            'options' => array(
                        'print' => __('紙本發票'),
                        '3J0002' => __('手機載具'),
                        'CQ0001' => __('自然人憑證條碼'),
                        'company' => __('公司統一編號'),
                        'charity' => __('捐贈發票')
            ),
            // 'default' => 'N', 
    ), $checkout->get_value( 'invoice_type' ) ); 
    woocommerce_form_field( 'tax_id', array(        
        'type' => 'text',        
        'class' => array( 'company form-row-wide' ),        
        'label' => '公司統一編號',   
        'placeholder' => '公司統編，例：12345678',
        'required' => false,        
        // 'default' => $tax_id,        
    ), $checkout->get_value( 'tax_id' ) ); 
    woocommerce_form_field( 'billing_company', array(        
        'type' => 'text',        
        'class' => array( 'company form-row-wide' ),        
        'label' => '公司名稱',  
        'placeholder' => '公司名稱，例：XXXX有限公司',
        'required' => false,        
    ), $checkout->get_value( 'billing_company' ) ); 
    woocommerce_form_field( 'carrier_id', array(        
        'type' => 'text',        
        'class' => array( 'e-invoice form-row-wide' ),        
        'label' => '載具號碼',        
        'placeholder' => '手機載具條碼，例：/ABC+123',
        'required' => false,        
    ), $checkout->get_value( 'carrier_id' ) ); 
    woocommerce_form_field( 'npo_ban', array(        
        'type' => 'select',        
        'class' => array( 'charity form-row-wide' ),        
        'label' => '捐贈單位',        
        'placeholder' => '請選擇捐贈單位……',
        'options' => $org_options,
        'required' => false,        
    ), $checkout->get_value( 'npo_ban' ) ); 
    ?>
    
    <script>
    function set_fields($) {
        // Hide textfield by default
        $( '.e-invoice' ).hide();
        $( '.company' ).hide();
        $( '.charity' ).hide();

        // Show fields by invoice type selected
        if ( $( 'select#invoice_type' ).val() == '3J0002' || $( 'select#invoice_type' ).val() == 'CQ0001' ) {
            $( '.e-invoice' ).show();
            // Set placeholder
            if ( $( 'select#invoice_type' ).val() == '3J0002' ) {
                $( 'input#carrier_id' ).attr('placeholder', '手機載具條碼，例：/ABC+123');
            } else {
                $( 'input#carrier_id' ).attr('placeholder', '自然人憑證條碼, 例：AB12345678901234');
            }
        } else if ($( 'select#invoice_type' ).val() == 'company') {
            $( '.company' ).show();
        } else if ($( 'select#invoice_type' ).val() == 'charity') {
            $( '.charity' ).show();
        }
    }
    
    jQuery(document).ready(function($) {
        // Set fields in the beginning
        set_fields($);

        // Set fields when selection changed
        $( 'select#invoice_type' ).change(() => set_fields($));
    });
    </script>
    <?php
}

add_action('woocommerce_checkout_process', 'custom_checkout_field_validation');

function custom_checkout_field_validation() {
    if( $_POST['invoice_type'] == 'company' ) {
        if (empty( $_POST['tax_id'] ) || empty( $_POST['billing_company'] )) {
            wc_add_notice( __( '請填寫公司統一編號及公司名稱' ), 'error' );
        } elseif ( !preg_match('/^[0-9]{8}$/', $_POST['tax_id']) ) {
            wc_add_notice( __( '公司統一編號格式錯誤' ), 'error' );
        }
    } elseif( $_POST['invoice_type'] == '3J0002' ) {
        if ( empty( $_POST['carrier_id'] ) ) {
            wc_add_notice( __( '請填寫手機載具條碼' ), 'error' );
        } else {
            $carrier_id = strtoupper($_POST['carrier_id']);
            if ( !preg_match('/^\/[A-Z0-9]{7}$/', $carrier_id) ) {
                wc_add_notice( __( '手機載具條碼格式錯誤' ), 'error' );
            }
        }
    } elseif( $_POST['invoice_type'] == 'CQ0001' ) {
        if ( empty( $_POST['carrier_id'] ) ) {
            wc_add_notice( __( '請填寫自然人憑證條碼' ), 'error' );
        } else {
            $carrier_id = strtoupper($_POST['carrier_id']);
            if ( !preg_match('/^[A-Z]{2}[0-9]{16}$/', $carrier_id) ) {
                wc_add_notice( __( '自然人憑證條碼格式錯誤' ), 'error' );
            }
        }
    } elseif( $_POST['invoice_type'] == 'charity' && empty( $_POST['npo_ban'] ) ) {
        wc_add_notice( __( '請選擇捐贈單位' ), 'error' );
    }
}

add_action( 'woocommerce_checkout_update_order_meta', 'save_new_checkout_field' );
  
function save_new_checkout_field( $order_id ) { 
    if ( $_POST['invoice_type'] ) update_post_meta( $order_id, 'invoice_type', esc_attr( $_POST['invoice_type'] ) );
    if ( $_POST['carrier_id'] ) update_post_meta( $order_id, 'carrier_id', esc_attr( strtoupper($_POST['carrier_id']) ) );
    if ( $_POST['npo_ban'] ) update_post_meta( $order_id, 'npo_ban', esc_attr( $_POST['npo_ban'] ) );
    if ( $_POST['tax_id'] ) update_post_meta( $order_id, 'tax_id', esc_attr( $_POST['tax_id'] ) );
    if ( $_POST['billing_company'] ) update_post_meta( $order_id, 'billing_company', esc_attr( $_POST['billing_company'] ) );
}
 