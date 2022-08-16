<?php // cpowcemail Settings callbacks

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

// callback: email section
function cpowcemail_callback_section_email() {
	
	echo '<p>These settings enable and customize the notification email.</p>';
	
}

// callback: text field
function cpowcemail_callback_field_text( $args ) {

	//error_log('In function cpowcemail_callback_field_text');

	
	$options = get_option( 'cpowcemail_options', cpowcemail_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';
	
	echo '<input id="cpowcemail_options_'. $id .'" name="cpowcemail_options['. $id .']" type="text" size="40" value="'. $value .'"><br />';
	echo '<label for="cpowcemail_options_'. $id .'">'. $label .'</label>';
	
}



// callback: textarea field
function cpowcemail_callback_field_textarea( $args ) {

	//error_log('In function cpowcemail_callback_field_textarea');

	
	$options = get_option( 'cpowcemail_options', cpowcemail_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$allowed_tags = wp_kses_allowed_html( 'post' );
	
	$value = isset( $options[$id] ) ? wp_kses( stripslashes_deep( $options[$id] ), $allowed_tags ) : '';
	
	echo '<textarea id="cpowcemail_options_'. $id .'" name="cpowcemail_options['. $id .']" rows="5" cols="50">'. $value .'</textarea><br />';
	echo '<label for="cpowcemail_options_'. $id .'">'. $label .'</label>';
	
}



// callback: checkbox field
function cpowcemail_callback_field_checkbox( $args ) {

	//error_log('In function cpowcemail_callback_field_checkbox');

	
	$options = get_option( 'cpowcemail_options', cpowcemail_options_default() );
	
	$id    = isset( $args['id'] )    ? $args['id']    : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$checked = isset( $options[$id] ) ? checked( $options[$id], 1, false ) : '';
	
	echo '<input id="cpowcemail_options_'. $id .'" name="cpowcemail_options['. $id .']" type="checkbox" value="1"'. $checked .'> ';
	echo '<label for="cpowcemail_options_'. $id .'">'. $label .'</label>';
	
}
?>
