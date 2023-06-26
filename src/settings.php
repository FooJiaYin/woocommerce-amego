<?php

add_action('admin_menu', 'settings_page');

function settings_page() {
    add_options_page(
        'Amego 設定',
        'Amego',
        'manage_options',
        'amego-settings',
        'settings_page_callback'
    );
}

// Callback function to render the plugin settings page
function settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>Amego 設定</h1>
        <form method="post" action="options.php">
            <?php settings_fields('amego-settings-group'); ?>
            <?php do_settings_sections('amego-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">統一編號 Invoice</th>
                    <td><input type="text" name="amego_invoice" value="<?php echo esc_attr(get_option('amego_invoice')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">App Key</th>
                    <td><input type="text" name="amego_app_key" value="<?php echo esc_attr(get_option('amego_app_key')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register and initialize the plugin settings
function amego_register_settings() {
    register_setting('amego-settings-group', 'amego_invoice');
    register_setting('amego-settings-group', 'amego_app_key');
}
add_action('admin_init', 'amego_register_settings');
