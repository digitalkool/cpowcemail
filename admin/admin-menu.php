<?php // CPOWCEmail - Admin Menu

// exit if file is called directly
if (!defined('ABSPATH')) {

    exit;
}


function cpowcemail_add_toplevel_menu()
{

    //error_log('In function cpowcemail_add_toplevel_menu');
    /*

    add_menu_page(
        string $page_title,
        string $menu_title,
        string $capability,
        string $menu_slug,
        callable $callback = '',
        string $icon_url = '',
        int|float $position = null
    );

    */

    add_menu_page(
        'CNCProfessional Online Woocommerce Email Settings',
        'WCEmail',
        'manage_options',
        'cpowcemail',
        'cpowcemail_display_settings_page',
        'dashicons-admin-generic',
        null
    );
}
add_action('admin_menu', 'cpowcemail_add_toplevel_menu');
