<?php // CPOWCEmail Settings register

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

// register plugin settings
function cpowcemail_register_settings() {

    //error_log('In function cpowcemail_register_settings');

    /*

    register_setting( 
        string   $option_group, 
        string   $option_name, 
        callable $sanitize_callback
    );

    */

    register_setting( 
        'cpowcemail_options', 
        'cpowcemail_options', 
        'cpowcemail_callback_validate_options' 
    ); 

    //email settings section
    add_settings_section( 
        'cpowcemail_section_email', 
        'Email Options', 
        'cpowcemail_callback_section_email', 
        'cpowcemail'
    );

    /*
    add_settings_field(
        string   $id,
        string   $title,
        callable $callback,
        string   $page,
        string   $section = 'default',
        array    $args = []
    );
    */

    /* email settings:
     * enable
     * sendto
     * from
     * subject
     * greeting
     * message
     * signature
     */

     add_settings_field(
        'email_enable',
        'Enable',
        'cpowcemail_callback_field_checkbox',
        'cpowcemail',
        'cpowcemail_section_email',
        [ 'id' => 'email_enable', 'label' => 'Check To Enable The Email Notification' ]
    );

    add_settings_field(
        'email_sendto',
        'Send To',
        'cpowcemail_callback_field_text',
        'cpowcemail',
        'cpowcemail_section_email',
        [ 'id' => 'email_sendto', 'label' => 'Send Email To' ]
    );

    add_settings_field(
        'email_from',
        'From',
        'cpowcemail_callback_field_text',
        'cpowcemail',
        'cpowcemail_section_email',
        [ 'id' => 'email_from', 'label' => 'Email From' ]
    );

    add_settings_field(
        'email_subject',
        'Subject',
        'cpowcemail_callback_field_text',
        'cpowcemail',
        'cpowcemail_section_email',
        [ 'id' => 'email_subject', 'label' => 'Email Subject' ]
    );

    add_settings_field(
        'email_greeting',
        'Greeting',
        'cpowcemail_callback_field_text',
        'cpowcemail',
        'cpowcemail_section_email',
        [ 'id' => 'email_greeting', 'label' => 'Email Greeting' ]
    );

    add_settings_field(
        'email_message',
        'Message',
        'cpowcemail_callback_field_textarea',
        'cpowcemail',
        'cpowcemail_section_email',
        [ 'id' => 'email_message', 'label' => 'The Email Message' ]
    );

    add_settings_field(
        'email_signature',
        'Signature',
        'cpowcemail_callback_field_textarea',
        'cpowcemail',
        'cpowcemail_section_email',
        [ 'id' => 'email_signature', 'label' => 'The Email Signature' ]
    );
    
}
add_action( 'admin_init', 'cpowcemail_register_settings' );
?>
