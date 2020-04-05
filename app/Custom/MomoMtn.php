<?php

/*
 * TODO
 * 1. Process
 * 2. Errors
 * 3. Dynamic currencies from woocommerce
 * 4. use database instead of sessions
 * 5. Connection failed error
 * 6. Work in French
 */

class MomoMtn extends WC_Payment_Gateway
{

    private $error_message;
    private $access_token;
    private $subscription_key = '';
    private $environment = "sandbox"; // mtncameroon, mtnug
    private $host = 'https://sandbox.momodeveloper.mtn.com'; // 'https://ericssonbasicapi1.azure-api.net/collection';

    // Setup our Gateway's id, description and other values
    function __construct()
    {
        // The global ID for this Payment method
        $this->id = "woomobipay";

        // The Title shown on the top of the Payment Gateways Page next to all the other Payment Gateways
        $this->method_title = __("MTN Mobile Money", 'woo-mobipay');

        // The description for this Payment Gateway, shown on the actual Payment options page on the backend
        $this->method_description = __("WooMobiPay Payment Gateway Plug-in for WooCommerce", 'woo-mobipay');

        // The title to be used for the vertical tabs that can be ordered top to bottom
        $this->title = __("MTN Mobile Money", 'woo-mobipay');

        $this->order_button_text = __("Pay with MTN Mobile Money", 'woo-mobipay');

        // If you want to show an image next to the gateway's name on the frontend, enter a URL to an image.
        $this->icon = null;

        // Bool. Can be set to true if you want payment fields to show on the checkout
        // if doing a direct integration, which we are doing in this case
        $this->has_fields = false;

        // Supports the default credit card form
        // $this->supports = array('default_credit_card_form');

        // This basically defines your settings which are then loaded with init_settings()
        // $this->init_form_fields();

        // // After init_settings() is called, you can get the settings and load them into variables, e.g:
        // // $this->title = $this->get_option( 'title' );
        // $this->init_settings();

        // // Turn these settings into variables we can use
        // foreach ($this->settings as $setting_key => $value) {
        //     $this->$setting_key = $value;
        // }

        // Let's check for SSL
        // add_action('admin_notices', array($this, 'do_ssl_check'));

        // // Let's check if we are on the WC Orders page
        // add_action('woocommerce_thankyou', array($this, 'update_order_status'));

        // // Save settings
        // if (is_admin()) {
        //     // Versions over 2.0
        //     // Save our administration options. Since we are not going to be doing anything special
        //     // we have not defined 'process_admin_options' in this class so the method in the parent
        //     // class will be used instead
        //     add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        // }
    }

    // End __construct()

    // Start session if it hasn't been started yet
    // public function start_session()
    // {
    //     if (!session_id()) {
    //         session_start();
    //     }
    // }

    // Check if we are forcing SSL on checkout pages
    // Custom function not required by the Gateway
    // public function do_ssl_check()
    // {
    //     if ($this->enabled == "yes") {
    //         if (get_option('woocommerce_force_ssl_checkout') == "no") {
    //             echo "<div class=\"error\"><p>" .
    //                 sprintf(
    //                         __("<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>", 'woo-mobipay'),
    //                         $this->method_title,
    //                         admin_url('admin.php?page=wc-settings&tab=checkout')
    //                 ) . "</p></div>";
    //         }
    //     }
    // }

    // Check if we are on the WC Orders page
    // then check if an order has just been processed successfully
    // and set the order status to paid
    public function update_order_status($order_id)
    {
        // Get the order
        $order = wc_get_order($order_id);

        $this->start_session();

        // Check if payment has really been made
        $reference_id = $_SESSION['reference_id'];


        if (isset($reference_id)) {
            // Check transaction status
            $response = $this->get_transfer_status($reference_id);

            $status_code = wp_remote_retrieve_response_code($response);

            if ($status_code === 200) {
                $body = wp_remote_retrieve_body($response);
                $parsedBody = json_decode($body);
                $status = $parsedBody->status;

                if ($status === "SUCCESSFUL") {
                    // Set order status to paid
                    $order->update_status('completed');
                } else if ($status === "PENDING") {
                    //
                } else if ($status === "FAILED" && $parsedBody->reason->code === "PAYER_NOT_FOUND") {
                    //
                }
            } else {
                // @TODO throw error
            }
        }
    }

    // // Build the administration fields for this specific Gateway
    // public function init_form_fields()
    // {
    //     $this->form_fields = array(
    //         'enabled' => array(
    //             'title' => __('Enable / Disable', 'woo-mobipay'),
    //             'label' => __('Enable this payment gateway', 'woo-mobipay'),
    //             'type' => 'checkbox',
    //             'default' => 'no',
    //         ),
    //         'sandbox' => array(
    //             'title' => __('Sandbox', 'woo-mobipay'),
    //             'label' => __('Enable sandbox mode', 'woo-mobipay'),
    //             'type' => 'checkbox',
    //             'default' => 'yes',
    //         ),
    //         'environment' => array(
    //             'title' => __('Environment', 'woo-mobipay'),
    //             'label' => __('Select your environment', 'woo-mobipay'),
    //             'type' => 'text',
    //             'default' => 'sandbox'
    //             /*'default' => 'option_b',
    //             'options'     => array(
    //                 'option_a' => __('option a', 'woocommerce' ),
    //                 'option_b' => __('option b', 'woocommerce' )
    //             )*/
    //         ),
    //         'title' => array(
    //             'title' => __('Title', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Payment title the customer will see during the checkout process.', 'woo-mobipay'),
    //             'default' => __('MTN Mobile Money', 'woo-mobipay'),
    //         ),
    //         'description' => array(
    //             'title' => __('Description', 'woo-mobipay'),
    //             'type' => 'textarea',
    //             'desc_tip' => __('Payment description the customer will see during the checkout process.', 'woo-mobipay'),
    //             'default' => __('Pay securely using your MTN mobile money account.', 'woo-mobipay'),
    //             'css' => 'max-width:350px;'
    //         ),
    //         'subscription_key' => array(
    //             'title' => __('Subscription key', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Key provided after subscribing to a MoMo product.', 'woo-mobipay')
    //         ),
    //         'api_user' => array(
    //             'title' => __('API User', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Key provided after creating an API user')
    //         ),
    //         'api_key' => array(
    //             'title' => __('API key', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Key provided after creating an API key')
    //         ),
    //         /*'spId' => array(
    //             'title' => __('Partner ID', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Partner ID,The ID is automatically allocated by the SDP to partners after successful registration.', 'woo-mobipay'),
    //         ),
    //         'api_details' => array(
    //             'title'       => __( 'API credentials', 'woocommerce' ),
    //             'type'        => 'title',
    //             'description' => sprintf( __( 'Enter your WooMobiPay API credentials to process refunds via mobile money. Learn how to access your <a href="%s">WooMobiPay API Credentials</a>.', 'woo-mobipay' ), 'https://developer.paypal.com/webapps/developer/docs/classic/api/apiCredentials/#creating-an-api-signature' ),
    //         ),
    //         'publicKey' => array(
    //             'title' => __('Public key', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('A public/private key pair is required to make transactions', 'woo-mobipay'),
    //         ),
    //         'privateKey' => array(
    //             'title' => __('Private key', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('A public/private key pair is required to make transactions', 'woo-mobipay'),
    //         ),*/
    //         /*'password' => array(
    //             'title' => __('Password', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Partner ID,The ID is automatically allocated by the SDP to partners after successful registration.', 'woo-mobipay'),
    //         ),
    //         'bundleID' => array(
    //             'title' => __('Bundle ID', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('Bundle ID', 'woo-mobipay'),
    //         ),
    //         'serviceId' => array(
    //             'title' => __('serviceId', 'woo-mobipay'),
    //             'type' => 'text',
    //             'desc_tip' => __('serviceId', 'woo-mobipay'),
    //         ),*/
    //     );
    // }

    // Display custom payment fields
    public function payment_fields()
    {
        ?>

        <!-- <fieldset>
            <p class="form-row form-row-wide">
                <label
                        for="<?php echo $this->id; ?>-admin-note"><?php __('Please provide your phone number.', 'woo-mobipay') ?>
                    <span
                            class="required">*</span></label>
                <input id="<?php echo $this->id; ?>-admin-note" class="input-text" type="text"
                       name="mobile-money-phone"/>
            </p>
            <div class="clear"></div>
        </fieldset> -->
        <?php
    }

    /**
     * Validate frontend fields.
     *
     * Validate payment fields on the frontend.
     *
     * @return bool
     */
    public function validate_fields()
    {
        $phone = $_POST['mobile-money-phone'];

        $is_ok = $this->validate_phone_number($phone);

        if ($is_ok == false) {
            wc_add_notice(__('Payment error: ', 'woo-mobipay') . $this->error_message, 'error');

            return false;
        } else {
            return true;
        }
    }

    /**
     * Get auth token
     *
     * @return string               Retrieved access token.
     */
    public function get_token()
    {

        $username = $this->get_option('api_user');
        $password = $this->get_option('api_key');

        $auth = $username . ':' . $password;
        $basic_token = 'Basic ' . base64_encode($auth);

        $args = [
            'body' => [],
            'headers' => [
                'Authorization' => $basic_token,
                'Ocp-Apim-Subscription-Key' => $this->subscription_key
            ]
        ];

        $response = wp_remote_post($this->host . '/collection/token/', $args);

        $body = wp_remote_retrieve_body($response);

        /*wc_add_notice(__('self::TOKEN_URL: ', 'woo-mobipay') . self::TOKEN_URL, 'error');
        wc_add_notice(__('$body: ', 'woo-mobipay') . $body, 'error');*/


        /*$parsedBody = json_decode($body);

        return $parsedBody->access_token;*/

        if ($body != null && !empty($body)) {
            $return = json_decode($body);

            if (isset($return->access_token) && $return->access_token != null && !empty($return->access_token)) {
                return $return->access_token;
            }
        }

        return '';

    } // end get_token

    /**
     * Request a payment from a customer
     *
     * @access public
     * @param  $amount              string amount to be transferred
     * @param  $currency            string currency of the transfer amount (ISO4217 Currency)
     * @param  $userIdType          string msisdn|email|party_code
     * @param  $userId              string The user number. Validated according to the user id type.
     * @param  $payerMessage        string Message that will be written in the payer transaction history message field.
     * @param  $payeeNote           string Message that will be written in the payee transaction history note field.
     * @param  $callbackUrl         string Callback URL
     * @param  $externalId          string External ID for transaction
     * @return $parsedResponse      json decoded JSON response or null if the request failed
     */
    public function request_to_pay(
        $amount, $currency, $userIdType, $userId, $payerMessage, $payeeNote, $callbackUrl, $externalId
    )
    {
        $amount = ceil((int)$amount) . '';

        $body = json_encode([
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $externalId,
            'payer' => [
                'partyIdType' => $userIdType,
                'partyId' => $userId
            ],
            'payerMessage' => $payerMessage,
            'payeeNote' => $payeeNote
        ]);

        // wc_add_notice(__('Request body: ', 'woo-mobipay') . json_encode($body), 'error');

        $access_token = $_SESSION['access_token'];

        // Set reference ID and place in session
        $reference_id = $this->gen_uuid();
        $_SESSION['reference_id'] = $reference_id;

        $headers = [
            'Authorization' => 'Bearer ' . $access_token,
            //'X-Callback-Url' => $callbackUrl,
            'X-Reference-Id' => $reference_id,
            'X-Target-Environment' => $this->environment,
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $this->subscription_key
        ];

        $args = [
            'body' => $body,
            'headers' => $headers
        ];

        $response = wp_remote_post($this->host . '/collection/v1_0/requesttopay', $args);

        // wc_add_notice(__('Transfer URL: ', 'woo-mobipay') . $this->host. '/collection/v1_0/requesttopay', 'error');
        // wc_add_notice(__('Response body: ', 'woo-mobipay') . wp_remote_retrieve_body($response), 'error');

        $status_code = wp_remote_retrieve_response_code($response);

        return $status_code;

        // $parsedBody = json_decode($body);

        // return $parsedBody;
    } // end request_to_pay

    public function verify_request_to_pay()
    {

        $access_token = $_SESSION['access_token'];
        $reference_id = $_SESSION['reference_id'];

        $headers = [
            'Authorization' => 'Bearer ' . $access_token,
            //'X-Reference-Id' => $reference_id,
            'X-Target-Environment' => $this->environment,
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $this->subscription_key
        ];

        $args = [
            // 'body' => $body,
            'headers' => $headers
        ];

        $url = $this->host . '/collection/v1_0/requesttopay/' . $reference_id;

        $response = wp_remote_get($url, $args);

        // wc_add_notice(__('Transfer URL: ', 'woo-mobipay') . $url, 'error');
        // wc_add_notice(__('Response body: ', 'woo-mobipay') . wp_remote_retrieve_body($response), 'error');

        return $response;
    }

    public function request_to_pay_loop() {

        $tries = 0;
        $TRY_INCREMENTS = 30;
        $MAX_TIME = 120;
        $MAX_TRIES = (int) ($MAX_TIME / $TRY_INCREMENTS);
        $message = '';
        $has_succeeded = false;

        // wc_add_notice(__('$MAX_TRIES', 'woo-mobipay') . $MAX_TRIES, 'error');

        // while ($tries * $TRY_INCREMENTS < $MAX_TIME) {
        while ($tries < $MAX_TRIES) { // $MAX_TRIES

            $tries ++;

            $response = $this->verify_request_to_pay();
            $status_code = wp_remote_retrieve_response_code($response);

            // wc_add_notice(__('Unknown error $status_code.', 'woo-mobipay') . $status_code, 'error');

            if ((int)$status_code >= 200 && (int)$status_code < 300) {

                $body = wp_remote_retrieve_body($response);
                $parsedBody = json_decode($body);

                $phone = $_POST['mobile-money-phone'];


                // Manually set response codes in sandbox as test numbers are not all giving the desired response
                /*switch ($phone) {
                    case '46733123450':
                        $status = 'FAILED';
                        break;
                    case '46733123451':
                        $status = 'REJECTED';
                        break;
                    case '46733123452':
                        $status = 'TIMEOUT';
                        break;
                    case '46733123453':
                        if ($tries > 1) {
                            $status = 'SUCCESSFUL';
                        } else {
                            $status = 'PENDING';
                        }
                        break;
                    case '46733123454':
                        $status = 'PENDING';
                        break;
                    default:
                        break;
                }*/

                $status = $parsedBody->status;

                if ($status == 'SUCCESSFUL') {
                    $message = '';
                    $has_succeeded = true;

                    break;
                } elseif ($status == 'PENDING') {
                    $message = __('Transaction timed out.', 'woo-mobipay');
                    sleep($TRY_INCREMENTS);
                    continue;
                } elseif ($status == 'TIMEOUT') {
                    $message = __('Transaction timed out.', 'woo-mobipay');
                    $has_succeeded = false;

                    break;
                } elseif ($status == 'REJECTED') {
                    $message = __('User rejected this transaction.', 'woo-mobipay');
                    $has_succeeded = false;

                    break;
                } elseif ($status == 'FAILED') {
                    $message = __('Transaction failed.', 'woo-mobipay');
                    $has_succeeded = false;

                    break;
                } else {
                    $message = __('Unknown error.', 'woo-mobipay');
                    $has_succeeded = false;
                    break;
                }
            } else {
                $message = __('Transaction failed.', 'woo-mobipay');
                $has_succeeded = false;
            }
        }

        return array(
          'has_succeeded' => $has_succeeded,
          'message' => $message
        );
    }

    public function process_payment($order_id)
    {
        $this->subscription_key = $this->get_option('subscription_key');

        $this->environment = (
            !empty($this->get_option('sandbox')) &&
            $this->get_option('sandbox') === 'no' &&
            !empty($this->get_option('environment'))
        ) ? $this->get_option('environment') : 'sandbox';

        $amount = WC()->cart->cart_contents_total;

        $phone = $_POST['mobile-money-phone'];

        if ($this->environment != 'sandbox') {
            $this->host = 'https://ericssonbasicapi1.azure-api.net';
            $phone = '237' . $phone; // TODO handle other countries
        }

        // Retrieve access token
        $access_token = $this->get_token();

        if ( !empty( $access_token ) ) {
            // Start session if not yet started
            $this->start_session();

            // Place access token in session
            $_SESSION['access_token'] = $access_token;

            // Set external ID
            $external_id = $order_id . '-' . time();

            $currency = ($this->environment == 'sandbox') ? 'EUR' : 'XAF';

            // Make MoMo payment
            $status_code = $this->request_to_pay(
                $amount,
                $currency,
                'MSISDN', $phone,
                'Payer message',
                'Payee note',
                null,
                $external_id
            );

            if ((int)$status_code === 202) {

                $loop_response = $this->request_to_pay_loop();

                if ($loop_response['has_succeeded'] === true) {
                    // Create a new order
                    $order = new WC_Order($order_id);

                    // Mark as paid
                    $order->payment_complete();

                    // Reduce stock levels
                    $order->reduce_order_stock();

                    // Remove cart
                    WC()->cart->empty_cart();

                    // Set the callback URL
                    $callback_url = $this->get_return_url($order);

                    return array(
                        'result' => 'success',
                        'redirect' => $callback_url
                    );
                } else {
                    wc_add_notice($loop_response['message'], 'error');
                }
            } else {
                wc_add_notice(__('Sorry, we were unable to initiate transaction. Please try again.', 'woo-mobipay'), 'error');
            }
        } else {
            wc_add_notice(__('You are unauthorized to access the MTN Mobile Money Gateway.', 'woo-mobipay'), 'error');
        }

        return array(
            'result' => 'danger',
        );


        /*if ($status_code === 202) {
            return true;
        } else {
            wc_add_notice(__('Payment error: ', 'woo-mobipay') . $status_code, 'error');

            return false;
        }*/

        // wc_add_notice(__('Payment error: ', 'woo-mobipay') . $order->get_total(), 'error');

        // return;
    }

    /**
     * Get the status of a transfer
     *
     * @access public
     * @param  $referenceId          string reference to the request
     * @return $parsedResponse       decoded JSON response or null if the request failed
     */
    public function get_transfer_status($referenceId)
    {
        $url = $this->host . '/v1_0/requesttopay/' . '/' . $referenceId;

        $access_token = $_SESSION['access_token'];

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'X-Target-Environment' => $this->environment,
                'Ocp-Apim-Subscription-Key' => $this->subscription_key
            ]
        ];

        $response = wp_remote_get($url, $args);

        return $response;
    } // end get_transfer_status

    /**
     * Returns true if phone number is valid
     * It returns false otherwise
     *
     * @param $phone
     * @return bool
     */
    private function validate_phone_number($phone)
    {
        $this->error_message = '';

        $stripped_phone = preg_replace("/[^0-9]/", '', $phone);

        if (!isset($stripped_phone) || empty($stripped_phone)) {

            if (!isset($phone) || empty($phone)) {
                $this->error_message = __('Phone number field is empty.', 'woo-mobipay');
            } else {
                $this->error_message = __('The phone number you provided is invalid.', 'woo-mobipay');
            }
        } elseif (!is_numeric($phone)) {
            $this->error_message = __('The phone number you provided is invalid.', 'woo-mobipay');
        } elseif (strlen($phone) < 8) {
            $this->error_message = __('The phone number you provided is short. Phone number cannot be less than 8 digits.', 'woo-mobipay');
            /*} elseif (strlen($phone) > 9) {
                $this->error_message = __('The phone number you provided is long.', 'woo-mobipay');*/
        } else {
            return true;
        }

        // $this->error_message .= ' ' . __('Please provide a 9 digit number. For example (677777777)', 'woo-mobipay'); // cameroon only

        return false;
    }

    /**
     * Generate a UUID v4
     *
     * @access private
     * @return string       Generated UUID v4
     */
    private function gen_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
