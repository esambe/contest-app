<?php

use dm\Dm_Database_Helper;

class WC_Gateway_Orange_Money extends WC_Payment_Gateway
{
    /**
     * @var Dm_Database_Helper
     */
    private $db;

    private $access_token;
    private $host = 'https://api.orange.com';

    // Setup our Gateway's id, description and other values
    function __construct()
    {
        // The global ID for this Payment method
        $this->id = "wooorangemoney";

        // The Title shown on the top of the Payment Gateways Page next to all the other Payment Gateways
        $this->method_title = __("Orange Money", 'woo-mobipay');

        // The description for this Payment Gateway, shown on the actual Payment options page on the backend
        $this->method_description = __("Orange Money Payment Gateway Plug-in for WooCommerce", 'woo-mobipay');

        // The title to be used for the vertical tabs that can be ordered top to bottom
        $this->title = __("Orange Money", 'woo-mobipay');

        $this->order_button_text = __("Pay with Orange Money", 'woo-mobipay');

        // If you want to show an image next to the gateway's name on the frontend, enter a URL to an image.
        $this->icon = null;

        // Bool. Can be set to true if you want payment fields to show on the checkout
        // if doing a direct integration, which we are doing in this case
        $this->has_fields = false;

        // Supports the default credit card form
        // $this->supports = array('default_credit_card_form');

        $this->init_database();

        // This basically defines your settings which are then loaded with init_settings()
        $this->init_form_fields();

        // After init_settings() is called, you can get the settings and load them into variables, e.g:
        // $this->title = $this->get_option( 'title' );
        $this->init_settings();

        // Turn these settings into variables we can use
        foreach ($this->settings as $setting_key => $value) {
            $this->$setting_key = $value;
        }

        // Let's check for SSL
        add_action('admin_notices', array($this, 'do_ssl_check'));

        // Let's check if we are on the WC Orders page
        add_action('woocommerce_thankyou', array($this, 'update_order_status'));

        // Save settings
        if (is_admin()) {
            // Versions over 2.0
            // Save our administration options. Since we are not going to be doing anything special
            // we have not defined 'process_admin_options' in this class so the method in the parent
            // class will be used instead
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }
    }
    // End __construct()

    public function init_database() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'orange_money_transaction';

        $create_sql = "CREATE TABLE " . $table_name . "  (
                  transaction_id BIGINT(20) NOT NULL AUTO_INCREMENT,
                  order_id BIGINT(20) NOT NULL,
                  pay_token varchar(252) NOT NULL,
                  notif_token varchar(252) NOT NULL,
                  PRIMARY KEY  (transaction_id)
                )";

        $this->db = new Dm_Database_Helper($table_name, $create_sql);
    }

    // Check if we are forcing SSL on checkout pages
    // Custom function not required by the Gateway
    // public function do_ssl_check()
    // {
    //     if ($this->enabled == "yes") {
    //         if (get_option('woocommerce_force_ssl_checkout') == "no") {
    //             echo "<div class=\"error\"><p>" . sprintf(__("<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>", 'woo-mobipay'), $this->method_title, admin_url('admin.php?page=wc-settings&tab=checkout')) . "</p></div>";
    //         }
    //     }
    // }

    // Build the administration fields for this specific Gateway
    // public function init_form_fields()
    // {
    //     $this->form_fields = array(
    //         'enabled' => array(
    //             'title' => __('Enable / Disable', 'woo-mobipay'),
    //             'label' => __('Enable this payment gateway', 'woo-mobipay'),
    //             'type' => 'checkbox',
    //             'default' => 'no',
    //         ),
    //         'title' => array(
    //             'title' => __('Title', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Payment title the customer will see during the checkout process.', 'woo-mobipay'),
    //             'default' => __('Orange Money', 'woo-mobipay'),
    //         ),
    //         'description' => array(
    //             'title' => __('Description', 'woo-mobipay'),
    //             'type' => 'textarea',
    //             'desc_tip' => __('Payment description the customer will see during the checkout process.', 'woo-mobipay'),
    //             'default' => __('Pay securely using your orange money account.', 'woo-mobipay'),
    //             'css' => 'max-width:350px;'
    //         ),
    //         'applicationID' => array(
    //             'title' => __('Application ID', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Application ID.', 'woo-mobipay'),
    //         ),
    //         'clientID' => array(
    //             'title' => __('Client ID', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Client ID.', 'woo-mobipay'),
    //         ),
    //         'clientSecret' => array(
    //             'title' => __('Client Secret', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Client Secret.', 'woo-mobipay'),
    //         ),
    //         'merchantKey' => array(
    //             'title' => __('Merchant Key', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Merchant Key', 'woo-mobipay'),
    //         ),
    //     );
    // }

    // Display custom payment fields
    // public function payment_fields()
    // {
    //     // echo '<p>';
    //     echo __('With Orange Money you can transfer money, even to those without a mobile phone or an Orange Money electronic wallet.', 'woo-mobipay');
    //     // echo '</p>';
    // }

    /**
     * Validate frontend fields.
     *
     * Validate payment fields on the frontend.
     *
     * @return bool
     */
    // public function validate_fields()
    // {
    //     // There are no fields to validate
    //     return true;
    // }

    /**
     * Get auth token
     *
     * @return string               Retrieved access token.
     */
    public function get_token()
    {
        $clientID = "";
        $clientSecret = "";

        $auth = $clientID . ':' . $clientSecret;
        $basic_token = 'Basic ' . base64_encode($auth);

        $args = [
            'body' => array(
                'grant_type' => 'client_credentials',
            ),
            'headers' => [
                'Authorization' => $basic_token,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ];

        $response = wp_remote_post($this->host . '/oauth/v2/token', $args);

        $body = wp_remote_retrieve_body($response);

        if ($body != null && !empty($body)) {
            $return = json_decode($body);

            if (isset($return->access_token) && $return->access_token != null && !empty($return->access_token)) {
                return $return->access_token;
            }
        }

        return '';
    } // end get_token

    public function getReturnUrl($redir_url) {
        return $redir_url;
    }

    public function request_to_pay($amount, $currency, $merchantKey, $order_id)
    {
        $amount = ceil((int)$amount) . '';

        // $order = new WC_Order($order_id);

        $body = json_encode([
            'merchant_key' => $merchantKey,
            'currency' => 'XAF',
            'order_id' => $order_id . '-' . time(),
            'amount' => '' . $amount,
            'return_url' => back()->with('status'),
            // 'return_url' => $this->get_return_url($order),
            //'return_url' => wc_get_checkout_url() . '?order_id=' . $order_id,
            'cancel_url' => wc_get_checkout_url(),
            // 'notif_url' => get_site_url() . '/wp-json/dm/notif_url',
            'notif_url' => back()->with('status'),
            'lang' => substr(get_locale(), 0, 2),
            'reference' => get_bloginfo('name'),
        ]);

        /*$body = json_encode([
            'merchant_key' => $merchantKey,
             'currency' => $currency,
            'order_id' => $order_id . '-' . time(),
            'amount' => '' . $amount,
            'return_url' => 'https://google.com',
            'cancel_url' => 'https://google.com',
            'notif_url' => 'https://google.com',
            'lang' => substr(get_locale(), 0, 2),
            'reference' => get_bloginfo('name'),
        ]);*/

        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $args = [
            'body' => $body,
            'headers' => $headers
        ];

        $response = wp_remote_post($this->host . '/orange-money-webpay/cm/v1/webpayment', $args);

        $body = wp_remote_retrieve_body($response);

        return json_decode($body);
    } // end request_to_pay


    // Processes payments
    public function process_payment($order_id)
    {
        $amount = WC()->cart->cart_contents_total;

        // Retrieve access token
        $this->access_token = $this->get_token();

        if ( !empty( $this->access_token ) ) {

            $merchantKey = $this->get_option('merchantKey', true);

            $payment_request = $this->request_to_pay($amount, 'XAF', $merchantKey, $order_id);

            if ($payment_request->status == 201) {

                $values = array(
                    'order_id' => $order_id,
                    'pay_token' => $payment_request->pay_token,
                    'notif_token' => $payment_request->notif_token
                );

                // Update transaction if it exists and create a new one if it does not exist

                $after_general_select = 'WHERE order_id="' . $order_id . '"';
                $result = $this->db->getMany(array(), $after_general_select);

                if (count($result) > 0) {
                    $where = array('order_id' => $order_id);
                    $this->db->update($values, $where);
                } else {
                    $this->db->insert($values);
                }

                return array(
                    'result' => 'success',
                    'redirect' => $payment_request->payment_url
                );
            } else {
                wc_add_notice(__('Sorry, we were unable to initiate transaction. Please try again.', 'woo-mobipay'), 'error');
            }
        } else {
            wc_add_notice(__('You are unauthorized to access the Orange Money Gateway.', 'woo-mobipay'), 'error');
        }

        return array(
            'result' => 'danger'
        );
    }
}

