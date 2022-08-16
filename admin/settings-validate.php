<?php // cpowcemail - Validate Settings

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

// callback: validate options
function cpowcemail_callback_validate_options( $input ) {

	error_log('In function cpowcemail_callback_validate_options');
	
	// email enable
	if ( ! isset( $input['email_enable'] ) ) {
		
		$input['email_enable'] = null;
		
	}
	
	$input['email_enable'] = ($input['email_enable'] == 1 ? 1 : 0);	
	
	// email sendto
	if ( isset( $input['email_sendto'] ) ) {
		
		$input['email_sendto'] = sanitize_text_field( $input['email_sendto'] );
		
	}
	
	// email from
	if ( isset( $input['email_from'] ) ) {
		
		$input['email_from'] = sanitize_text_field( $input['email_from'] );
		
	}

	// email subject
	if ( isset( $input['email_subject'] ) ) {
		
		$input['email_subject'] = sanitize_text_field( $input['email_subject'] );
		
	}
	
	// email greeting
	if ( isset( $input['email_greeting'] ) ) {
		
		$input['email_greeting'] = sanitize_text_field( $input['email_greeting'] );
		
	}


	// email message
	if ( isset( $input['email_message'] ) ) {
		
		$input['email_message'] = wp_kses_post( $input['email_message'] );
		
	}

	// email signature
	if ( isset( $input['email_signature'] ) ) {
		
		$input['email_signature'] = wp_kses_post( $input['email_signature'] );
		
	}

	
	return $input;
	
}
?>
