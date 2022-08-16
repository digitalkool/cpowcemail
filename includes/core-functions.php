<?php // CPOWCEMAIL - Core Functionality

// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
	
}

//customize woocommerce emails header
function cpo_wcemail_emails_before_header( $email_heading, $email ){
    $GLOBALS['email'] = $email;
}

add_action( 'woocommerce_email_header', 'cpo_wcemail_emails_before_header', 1,3 );

//custom email header per product by product id
function cpo_wcemail_custom_header_per_product_id( $email_heading, $order ) {
    global $woocommerce;
    $items = $order->get_items();
    foreach ( $items as $item ) {
        $product_id = $item['product_id'];
        if ( $product_id == 87 ) {
            $email_heading = 'WooCommerce email notification OR how to test WooCommerce emails';
        }
        return $email_heading;
    }
}
add_filter( 'woocommerce_email_heading_customer_processing_order', 'cpo_wcemail_custom_header_per_product_id', 10, 5 );

// function used to compose email to send to Camsoft
function cpo_wcemail_Send_Email_To_Camsoft($id){

    //$logger = wc_get_logger();
    //$log = 'In cpo_wcemail_Send_Email_To_Camsoft' . "\r\n";
    //$log = $log . 'ID: ' . $id;
    //$logger->debug($log);

    // load cpowcemail options
    $options = get_option( 'cpowcemail_options', cpowcemail_options_default() );

    //check to see if this is enabled
    if ( isset( $options['email_enable']) && ! empty( $options['email_enable'])){
        
        //$log = 'Enable option is set';
        //$logger->debug($log);

        /*Check to see if this is a camsoft product order then send message to camsoft
        *check for product id
        */
        $order = wc_get_order($id);
        
        $items = $order->get_items();
        $dosendnotification = false;

        $order_item_name;
        $order_item_quantity;

        
        foreach($items as $item){
            $productid=$item['product_id'];

            if($productid == 541 || $product_id == 591 || $product_id == 592){
                $order_item_name = $item->get_name();
                $order_item_quantity = $item->get_quantity();
                $dosendnotification = true;
            } 
        }
        
        if($dosendnotification == false)
        {
            //$log = "Do not send notification";
            //$logger->debug($log);
            return $dosendnotification;
        }

        //$log = 'This is a Camsoft product' . "\r\n";
        //$logger->debug($log);

        $customer_fname = $order->get_billing_first_name('view');
        //$logger->debug($customer_fname);
        $customer_lname = $order->get_billing_last_name('view');
        //$logger->debug($customer_lname);
        $customer_email = $order->get_billing_email('view');
        //$logger->debug($customer_email);
        $order_details = "Order Details: \r\n";
        $order_details = $order_details . 'Customer Name: ' . $customer_fname . ' ' . $customer_lname . "\r\n";
        $order_details = $order_details . 'Customer email: ' . $customer_email . "\r\n";
        $order_details = $order_details . $order_item_quantity . ' ' . $order_item_name . "\r\n";
        //$logger->debug($order_details);
        
        
        // get send to address
        if ( isset( $options['email_sendto']) && ! empty( $options['email_sendto'])){
            $to = $options['email_sendto'];
        }

        if ( isset( $options['email_from']) && ! empty( $options['email_from'])){
            $emailheader[] = $options['email_from'];
        }

        if ( isset( $options['email_subject']) && ! empty( $options['email_subject'])){
            $subject = $options['email_subject'];
        }

        if ( isset( $options['email_greeting']) && ! empty( $options['email_greeting'])){
            $message = $options['email_greeting'] . "\r\n";
        }

        if ( isset( $options['email_message']) && ! empty( $options['email_message'])){
            $message = $message . $options['email_message'] . "\r\n";
        }

        $message = $message . $order_details;

        if ( isset( $options['email_signature']) && ! empty( $options['email_signature'])){
            $message = $message . $options['email_signature'];
        }

        //$logger->debug($message);

        $emailheader[] = 'Content-type: text/plain';

        $result = wp_mail($to, $subject, $message, $emailheader );

        if($result == false)
        {
            //$log = 'Email not sent' . "\r\n";
            //$logger->debug($log);
        }
        return $result;
    }
    return false;
}
add_action( 'woocommerce_payment_complete', 'cpo_wcemail_Send_Email_To_Camsoft', 10, 2 );
?>