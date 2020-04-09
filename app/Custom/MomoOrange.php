<?php

namespace App\Custom;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

use function GuzzleHttp\json_decode;

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
            $response = $client->post(
                $this->host . '/oauth/v2/token', [
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                    ],

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

    public function requestToPay($amount) {

        $amount = ceil((int)$amount). '';
        $merchantKey = $this->merchantKey;
        $order_id = 'voter';

        $body = json_encode([
            'merchant_key' => $merchantKey,
            'currency' => 'XAF',
            'order_id' => $order_id . '-' . time(),
            'amount' => '' . $amount,
            'return_url' => 'https://google.com',
            // 'return_url' => $this->get_return_url($order),
            //'return_url' => wc_get_checkout_url() . '?order_id=' . $order_id,
            'cancel_url' => 'https://google.com',
            // 'notif_url' => get_site_url() . '/wp-json/dm/notif_url',
            'notif_url' => 'https://google.com',
            'lang' => 'en',
            'reference' => 'Vote App',
        ]);

        $headers = [
            'Authorization' => 'Bearer ' . $this->get_token(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $args = [
            'body' => $body,
            'headers' => $headers,
            'timeout' => 259,
            'connect_timeout' => 259
        ];

        $http_client = new Client();
        $resp = $http_client->request(
            'POST', $this->host . '/orange-money-webpay/cm/v1/webpayment', $args
        );

        return json_decode($resp->getBody()->getContents());
    }

    // Processes payments
    public function process_payment($order_id, $amount)
    {
        // Retrieve access token
        $access_token = $this->get_token();

        if ( !empty( $access_token ) ) {

            // $merchantKey = $this->merchantKey;

            $payment_request = $this->requestToPay($amount);

            if ($payment_request->status == 201) {
                return array(
                    'result' => 'success',
                    'redirect' => $payment_request->payment_url
                );
            } else {
                return back()->with('danger', 'Sorry, we were unable to initiate transaction. Please try again.');
            }
        } else {
            return back()->with('danger', '');
        }

        return array(
            'result' => 'danger'
        );
    }
}

