<?php

namespace App\Custom;
use GuzzleHttp\Client;

class MomoOrange
{

    private $access_token;
    private $host = 'https://api.orange.com';
    private $clientID = "Ob63oDG9iWA7KeY7XDVLpSXlcDQhV1Un";
    private $clientSecret = "0j12PtBkvHAOAC4O";
    private $merchantKey = "ff0e0c95";


    // Setup our Gateway's id, description and other values
    function __construct()
    {}
    // End __construct()



    /**
     * Get auth token
     *
     * @return string               Retrieved access token.
     */
    public function get_token()
    {
        $clientID = $this->clientID;
        $clientSecret = $this->clientSecret;

        $auth = $clientID . ':' . $clientSecret;
        $basic_token = 'Basic ' . base64_encode($auth);

        $client = new Client();

        try {
            $response = $client->request(
                'POST', $this->host . '/oauth/v2/token', [
                    'body' => array(
                        'grant_type' => 'client_credentials',
                    ),
                    'headers' => [
                        'Authorization' => $basic_token,
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ]
                ]
            );

            $data =  json_decode($response->getBody(), true);
            return $data['access_token'];

        } catch (RequestException $ex) {
            abort(501, $ex->getMessage());
        }
    } // end get_token

    public function getReturnUrl($redir_url) {
        return $redir_url;
    }

    public function requestToPay($amount, $currency, $order_id, $ret_url) {

        $amount = ceil((int)$amount) . '';
        $merchantKey = $this->merchantKey;


        $headers = [
            'Authorization' => 'Bearer ' . $this->get_token(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        // if($collection_call_back_url != null) {
        //     $headers['X-Callback-Url'] = $collection_call_back_url;
        // }

        $http_client = new Client();

        try {
            $response = $http_client->request(
                'POST', $this->host . '/orange-money-webpay/cm/v1/webpayment', [
                    'headers' => $headers,
                    'json' => [
                        'merchant_key' => $merchantKey,
                        'currency' => 'XAF',
                        'order_id' => $order_id . '-' . time(),
                        'amount' => '' . $amount,
                        'return_url' => $this->getReturnUrl($ret_url),
                        // 'return_url' => $this->get_return_url($order),
                        //'return_url' => wc_get_checkout_url() . '?order_id=' . $order_id,
                        'cancel_url' => $this->getReturnUrl($ret_url),
                        // 'notif_url' => get_site_url() . '/wp-json/dm/notif_url',
                        'notif_url' => $this->getReturnUrl($ret_url),
                        'lang' => substr(get_locale(), 0, 2),
                        'reference' => 'Vote',
                    ],
                    'verify' => false,
                    'connect_timeout' => '259',
                    'timeout ' => '259'
                ]
            );

        $data =  json_decode($response->getBody(), true);
        return $data;

        } catch (\Throwable $ex) {
            abort(501, $ex->getMessage());
        }
    }

    // public function request_to_pay($amount, $currency, $order_id)
    // {
    //     $amount = ceil((int)$amount) . '';
    //     $merchantKey = "ff0e0c95";

    //     // $order = new WC_Order($order_id);

    //     $body = json_encode([
    //         'merchant_key' => $merchantKey,
    //         'currency' => 'XAF',
    //         'order_id' => $order_id . '-' . time(),
    //         'amount' => '' . $amount,
    //         'return_url' => '',
    //         // 'return_url' => $this->get_return_url($order),
    //         //'return_url' => wc_get_checkout_url() . '?order_id=' . $order_id,
    //         'cancel_url' => '',
    //         // 'notif_url' => get_site_url() . '/wp-json/dm/notif_url',
    //         'notif_url' => '',
    //         'lang' => 'en',
    //         'reference' => 'vote',
    //     ]);

    //     $headers = [
    //         'Authorization' => 'Bearer ' . $this->access_token,
    //         'Content-Type' => 'application/json',
    //         'Accept' => 'application/json'
    //     ];

    //     $args = [
    //         'body' => $body,
    //         'headers' => $headers
    //     ];

    //     $response = wp_remote_post($this->host . '/orange-money-webpay/cm/v1/webpayment', $args);

    //     $body = wp_remote_retrieve_body($response);

    //     return json_decode($body);
    // }


    // Processes payments
    // public function process_payment($order_id)
    // {
    //     $amount = WC()->cart->cart_contents_total;

    //     // Retrieve access token
    //     $this->access_token = $this->get_token();

    //     if ( !empty( $this->access_token ) ) {

    //         $merchantKey = $this->get_option('merchantKey', true);

    //         $payment_request = $this->request_to_pay($amount, 'XAF', $merchantKey, $order_id);

    //         if ($payment_request->status == 201) {

    //             $values = array(
    //                 'order_id' => $order_id,
    //                 'pay_token' => $payment_request->pay_token,
    //                 'notif_token' => $payment_request->notif_token
    //             );

    //             // Update transaction if it exists and create a new one if it does not exist

    //             $after_general_select = 'WHERE order_id="' . $order_id . '"';
    //             $result = $this->db->getMany(array(), $after_general_select);

    //             if (count($result) > 0) {
    //                 $where = array('order_id' => $order_id);
    //                 $this->db->update($values, $where);
    //             } else {
    //                 $this->db->insert($values);
    //             }

    //             return array(
    //                 'result' => 'success',
    //                 'redirect' => $payment_request->payment_url
    //             );
    //         } else {
    //             wc_add_notice(__('Sorry, we were unable to initiate transaction. Please try again.', 'woo-mobipay'), 'error');
    //         }
    //     } else {
    //         wc_add_notice(__('You are unauthorized to access the Orange Money Gateway.', 'woo-mobipay'), 'error');
    //     }

    //     return array(
    //         'result' => 'danger'
    //     );
    // }
}

