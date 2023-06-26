<?php
/*
Plugin Name: WooCommerce Amego
Plugin URI: https://github.com/FooJiaYin/woocommerce-amego
Version: 1.1.0
Author: FooJiaYin
Author URI: https://github.com/FooJiaYin
Description: WooCommerce 串接 Amego 電子發票平台
*/

// Include the plugin settings file
require_once plugin_dir_path(__FILE__) . 'src/settings.php';
require_once plugin_dir_path(__FILE__) . 'src/log.php';
require_once plugin_dir_path(__FILE__) . 'src/test.php';
require_once plugin_dir_path(__FILE__) . 'src/checkout_fields.php';
require_once plugin_dir_path(__FILE__) . 'src/send_invoice.php';