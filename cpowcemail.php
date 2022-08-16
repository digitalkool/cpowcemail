<?php
/*
Plugin Name:       CPOWCEmail
Description:       A plugin to add additional email notices to woocommerce
Plugin URI:        
Contributors:      
Author:            Ruben Ordonez
Author URI:        https://rubenordonez.com/
Donate link:       
Tags:              woocommerce, email
Version:           1.0
Stable tag:        1.0
Requires at least: 4.5
Tested up to:      4.8
Text Domain:       cpo-wcemail
Domain Path:       /languages
License:           GPL v2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.txt

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 
2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
with this program. If not, visit: https://www.gnu.org/licenses/
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

// if admin area
if ( is_admin() ) {

    // include dependencies
    require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callbacks.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-validate.php';
}


// include plugin dependencies: admin and public
require_once plugin_dir_path( __FILE__ ) . 'includes/core-functions.php';


// default plugin options
function cpowcemail_options_default() {

    return array(
        'email_enable' => false,
        'email_sendto' => 'sales@camsoftcorp.com',
        'email_from' => 'sales@cncprofessional.online',
        'email_subject' => 'New AS3000 Package Sold',
        'email_greeting' => 'Hello Camsoft:',
        'email_message'  => 'Congradulations.  A new AS3000 package sold!',
        'email_signature' => 'CNCPROFESSIONAL ONLINE',
    );
}
?>
