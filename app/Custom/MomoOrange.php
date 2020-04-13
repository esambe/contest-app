<?php

namespace App\Custom;

use App\OrangeMomoTransaction;
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
    private $app_host = 'http://votes.marketplaz.com/public';
    // private $localhost = 'http://127.0.0.1';


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

    function slugify($string){
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    public function requestToPay($amount, $contest_id, $title) {

        $amount = ceil((int)$amount). '';
        $merchantKey = $this->merchantKey;
        $order_id = 'voter';

        $body = json_encode([
            'merchant_key' => $merchantKey,
            'currency' => 'XAF',
            'order_id' => $order_id . '-' . time(),
            'amount' => '' . $amount,
            'return_url' =>  $this->app_host. '/contest/contestant/'. $contest_id . '-'. $this->slugify($title),
            // 'return_url' => $this->get_return_url($order),
            //'return_url' => wc_get_checkout_url() . '?order_id=' . $order_id,
            'cancel_url' => $this->app_host . '/contest/contestant/'. $contest_id . '-'. $this->slugify($title),
            // 'notif_url' => get_site_url() . '/wp-json/dm/notif_url',
            'notif_url' => $this->app_host .'/orange-callback',
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
    public function process_payment($amount, $contest_id, $title)
    {
        // Retrieve access token
        $access_token = $this->get_token();

        if ( !empty( $access_token ) ) {

            // $merchantKey = $this->merchantKey;

            $payment_request = $this->requestToPay($amount, $contest_id, $title);


            if ($payment_request->status == 201) {
                return [
                    'result' => 'success',
                    'pay_token' => $payment_request->pay_token,
                    'notif_token' => $payment_request->notif_token,
                    'redirect' => $payment_request->payment_url
                ];
            } else {
                return [
                    'result' => 'fail'
                ];
            }
        } else {
            return [
                'result' => 'error'
            ];
        }

        return array(
            'result' => 'danger'
        );
    }


    public function checkTransactionStatus($order_id, $amount, $pay_token)
    {

        $body = [
            "order_id"  => $order_id,
            "amount"    => $amount,
            "pay_token" => $pay_token
        ];

        $body = json_encode($body);

        $clientID = $this->clientID;
        $clientSecret = $this->clientSecret;
        $auth = $clientID . ':' . $clientSecret;

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . base64_encode($auth),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json'
            ],
            'body' => $body
        ];
        $client = new Client();
        return  $client->request('POST', $this->host .'orange-money-webpay/dev/v1/transactionstatus', $options);
    }

}

