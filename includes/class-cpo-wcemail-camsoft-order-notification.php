<?php
/**
 * Class CPO_WCEmail_Camsoft_Order_Notification file
 *
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Email' ) ) :
/**
 * New AS3000 Order Email.
 *
 * An email sent to the admin when a new AS3000 order is received/paid for.
 *
 * @class       CPO_WC_Email_New_AS3000_Order
 * @version     1.0.0
 * @package     CPOWC
 * @extends     Woocommerce WC_Email class
 */
class CPO_WCEmail_Camsoft_Order_Notification extends WC_Email 
{

	/**
	 * WC logger.
	 *
	 * @var string
	 */
	private $logger;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->logger = wc_get_logger();

		//$log = "CPO_WCEmail Caamsoft order notifications class -In contruct function" . "\r\n";

		//unique id for custom email
		$this->id             = 'camsoft_notification';

		//title field in woocommerce email settings 
		$this->title          = __( 'Camsoft Notification', 'woocommerce' );

		//description field in woocommerce email settings
		$this->description    = __( 'New Camsoft product order notification emails are sent to chosen recipient(s) when a new Camsoft Product order is received.', 'woocommerce' );

		//these define the location of the template file this should use
		$this->template_base  = plugin_dir_path( __DIR__ );	// Fix the template base lookup for use on admin screen template path display
		//$log = $log . "Template Base: " . $this->template_base . "\r\n";
		$this->template_html  = 'emails/html/camsoft-order-notification.php';
		//$log = $log . "Template HTML: " . $this->template_html . "\r\n";
		$this->template_plain = 'emails/plain/camsoft-order-notification.php';
		//$log = $log . "Template Plain: " . $this->template_plain . "\r\n";

		//$this->logger->debug($log);

		$this->placeholders   = array(
			'{order_date}'   => '',
			'{order_number}' => '',
		);

		// Triggers for this email.
		add_action( 'woocommerce_payment_complete', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_pending_to_completed_notification', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_failed_to_completed_notification', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_cancelled_to_completed_notification', array( $this, 'trigger' ), 10, 2 );
		//add_action( 'woocommerce_order_status_cancelled_to_on-hold_notification', array( $this, 'trigger' ), 10, 2 );

		// Call parent constructor.
		parent::__construct();

		// Other settings.
		$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
	}

	/**
	 * Get email subject.
	 *
	 * @since  3.1.0
	 * @return string
	 */
	public function get_default_subject() {
		//return __( '[{site_title}]: New AS3000 order #{order_number}', 'woocommerce' );
		return __( 'Camsoft Product Order Notice', 'woocommerce' );
	}

	/**
	 * Get email heading.
	 *
	 * @since  3.1.0
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Camsoft Product Order Notice', 'woocommerce' );
	}

	/**
	 * Prepares email content and Trigger the sending of this email.
	 *
	 * @param int            $order_id The order ID.
	 * @param WC_Order|false $order Order object.
	 */
	public function trigger( $order_id, $order = false ) {


		$this->logger->debug('in trigger function for camsoft email');

		$this->setup_locale();

		if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
			$order = wc_get_order( $order_id );
		}

		if ( is_a( $order, 'WC_Order' ) ) {
			$this->object                         = $order;
			$this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
			$this->placeholders['{order_number}'] = $this->object->get_order_number();
			

			$email_already_sent = $order->get_meta( '_camsoft_order_notification_email_sent' );
		}

		
		$this->logger->debug('before check for resend multiple emails');
		/**
		 * Controls if new order emails can be resend multiple times.
		 *
		 * @since 5.0.0
		 * @param bool $allows Defaults to false.
		 */
		if ( 'true' === $email_already_sent && ! apply_filters( 'woocommerce_new_order_email_allows_resend', false ) ) {
			return;
		}

		$this->logger->debug('after check for resedn multiple eamils');



		/*Controls if this is a camsoft product order then send message to camsoft
		 *check for product id
		 */
		
		 $items = $order->get_items();
		 $dosendnotification = false;
		 
		 
		 foreach($items as $item){
			$productid=$item['product_id'];

			if($productid == 541) $dosendnotification = true;
			if($productid == 591) $dosendnotification = true;
			if($productid == 59) $dosendnotification = true;

		 }
		 
		if($dosendnotification == false) return;

		$this->logger->debug('after check for product id');

		if ( $this->is_enabled() && $this->get_recipient() ) {

			$this->logger->debug('about to send email');

			$eresult = $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

			if($eresult == false) $this->logger->debug('email not sent');

			$order->update_meta_data( '_camsoft_order_notification_email_sent', 'true' );
			$order->save();
		}

		$this->restore_locale();
	}

	/**
	 * Get content html.
	 *
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html(
			$this->template_html,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => true,
				'plain_text'         => false,
				'email'              => $this,
			)
		);
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html(
			$this->template_plain,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => true,
				'plain_text'         => true,
				'email'              => $this,
			)
		);
	}

	/**
	 * Default content to show below main email content.
	 *
	 * @since 3.7.0
	 * @return string
	 */
	public function get_default_additional_content() {
		return __( 'Congratulations on the sale.', 'woocommerce' );
	}

	/**
	 * Initialise settings form fields.
	 */
	public function init_form_fields(){
		/* translators: %s: list of placeholders */
		$placeholder_text  = sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>' . implode( '</code>, <code>', array_keys( $this->placeholders ) ) . '</code>' );
		$this->form_fields = array(
			'enabled'            => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this email notification', 'woocommerce' ),
				'default' => 'yes',
			),
			'recipient'          => array(
				'title'       => __( 'Recipient(s)', 'woocommerce' ),
				'type'        => 'text',
				/* translators: %s: WP admin email */
				'description' => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'woocommerce' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
				'placeholder' => '',
				'default'     => '',
				'desc_tip'    => true,
			),
			'subject'            => array(
				'title'       => __( 'Subject', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_subject(),
				'default'     => '',
			),
			'heading'            => array(
				'title'       => __( 'Email heading', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_heading(),
				'default'     => '',
			),
			'additional_content' => array(
				'title'       => __( 'Additional content', 'woocommerce' ),
				'description' => __( 'Text to appear below the main email content.', 'woocommerce' ) . ' ' . $placeholder_text,
				'css'         => 'width:400px; height: 75px;',
				'placeholder' => __( 'N/A', 'woocommerce' ),
				'type'        => 'textarea',
				'default'     => $this->get_default_additional_content(),
				'desc_tip'    => true,
			),
			'email_type'         => array(
				'title'       => __( 'Email type', 'woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'     => 'html',
				'class'       => 'email_type wc-enhanced-select',
				'options'     => $this->get_email_type_options(),
				'desc_tip'    => true,
			),
		);
	}


	/**
	 * Override parent Send an email.
	 *
	 * @param string $to Email to.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param string $headers Email headers.
	 * @param array  $attachments Email attachments.
	 * @return bool success
	 */
	public function send( $to, $subject, $message, $headers, $attachments ) {

		$log = 'in email send function';
		$this->logger->debug($log);
		
		$log='To: '. print_r($to,true) . "\r\n";
		$log= $log . 'Headers: '. print_r($headers,true) . "\r\n";
		$log= $log . 'Subject: '. print_r($subject,true) . "\r\n";
		$log= $log . 'Message: '. print_r($message,true) . "\r\n";		

		$this->logger->debug($log);

				
		//$to = 'ruben@cncprofessional.online';
		//$subject = 'New AS3000 sale at CNCPROFESSIONAL.ONLINE';
		//$message = 'There was a new sale for an AS3000 package at CNCPROFESSIONAL.ONLINE';
		//$emailheader[] = 'Content-type: text/plain';
		//$emailheader[] = 'From: sales@cncprofessional.online';


		//$return = wp_mail($to, $subject, $message, $emailheader );

		
		add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		$message              = apply_filters( 'woocommerce_mail_content', $this->style_inline( $message ) );
		$mail_callback        = apply_filters( 'woocommerce_mail_callback', 'wp_mail', $this );
		$mail_callback_params = apply_filters( 'woocommerce_mail_callback_params', array( $to, wp_specialchars_decode( $subject ), $message, $headers, $attachments ), $this );
		$return               = $mail_callback( ...$mail_callback_params );

		

		remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		/**
		 * Action hook fired when an email is sent.
		 *
		 * @since 5.6.0
		 * @param bool     $return Whether the email was sent successfully.
		 * @param int      $id     Email ID.
		 * @param WC_Email $this   WC_Email instance.
		 */
		//do_action( 'woocommerce_email_sent', $return, $this->id, $this );

		return $return;
	}



}


endif;

//return new WC_Email_New_Order();
