<?php
namespace App\Custom;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class MomoMtn {


    const USER_ID = 'efa1ae8d-7226-4eda-9be7-8cb74a1e2279';
    const API_KEY = 'e933cfa2a22b4302abf246f3487465f7';
    const TOKEN_URL = 'https://ericssonbasicapi1.azure-api.net/collection/token/';
    const SUB_KEY   = 'd5ea6daf31b14c0ab2d230e1c94291f4';

    public static function getCollectionToken()
    {

        $user_id =  MomoMtn::USER_ID;
        $api_key = MomoMtn::API_KEY;
        $token_url =  MomoMtn::TOKEN_URL;
        $ocp_apim_sub_key = MomoMtn::SUB_KEY;

        $client = new Client();
        try {
            $response = $client->request(
                'POST', $token_url, [
                    'headers' => [
                        'Authorization' => 'Basic '.base64_encode($user_id.':'.$api_key),
                        'Ocp-Apim-Subscription-Key' => $ocp_apim_sub_key
                    ],
                    'json' => [
                        'grant_type' => 'client_credentials',
                    ],
                ]
            );

            $data =  json_decode($response->getBody(), true);
            return $data['access_token'];

        } catch (RequestException $ex) {
            abort(501, $ex->getMessage());
        }

    }


    public function requestToPay($party_id, $amount, $payer_message = '', $payee_note = '') {

        $momo_transaction_id = (string) Str::uuid();
        $http_client = new Client();

        $currency = 'XAF';
        $transaction_id = (string) Str::uuid();
        $party_id_type = 'MSISDN';
        $environment = 'mtncameroon';
        $collection_transaction_url = 'https://ericssonbasicapi1.azure-api.net/collection/v1_0/requesttopay';
        //https://ericssonbasicapi1.azure-api.net/collection/v1_0/requesttopay
        //https://ericssonbasicapi1.azure-api.net/collection
        $collection_ocp_apim_sub_key = 'd5ea6daf31b14c0ab2d230e1c94291f4';
        $collection_call_back_url = '';

        $headers = [
            'X-Reference-Id' => $momo_transaction_id,
            'X-Target-Environment' => $environment,
            'Authorization' => 'Bearer ' . $this::getCollectionToken(),
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $collection_ocp_apim_sub_key
        ];

        if($collection_call_back_url != null) {
            $headers['X-Callback-Url'] = $collection_call_back_url;
        }

        try {
            $http_client->request(
                'POST', $collection_transaction_url, [
                    'headers' => $headers,
                    'json' => [
                        'amount' => $amount,
                        'currency' => $currency,
                        'externalId' => $transaction_id,
                        'payer' => [
                            'partyIdType' => $party_id_type,
                            'partyId' => $party_id,
                        ],
                        'payerMessage' => $payer_message,
                        'payeeNote' => $payee_note,
                    ],
                    'verify' => false,
                    'connect_timeout' => '259',
                    'timeout ' => '259'
                ]
            );
        return $momo_transaction_id;

        } catch (\Throwable $ex) {
            abort(501, $ex->getMessage());
        }
    }
    public function getCollectionTransactionStatus($momo_transaction_id)
    {
        $environment = 'mtncameroon';
        $collection_transaction_status_url = 'https://ericssonbasicapi1.azure-api.net/collection/v1_0/requesttopay/{momo_transaction_id}';
        $collection_transaction_status_url = str_replace('{momo_transaction_id}', $momo_transaction_id, $collection_transaction_status_url);
        $collection_ocp_apim_sub_key = 'd5ea6daf31b14c0ab2d230e1c94291f4';

        $client = new Client();

        try {
            $response = $client->request(
                'GET', $collection_transaction_status_url, [
                'headers' => [
                    'X-Target-Environment' => $environment,
                    'Authorization' => 'Bearer ' . $this::getcOllectionToken(),
                    'Ocp-Apim-Subscription-Key' => $collection_ocp_apim_sub_key
                    ],
                'connect_timeout' => '259',
                'timeout ' => '259',
                'verify' => false
                ]
            );
            return json_decode($response->getBody(), true);

        } catch (RequestException $ex) {
            abort(501, $ex->getMessage());
            // throw new CollectionRequestException('Unable to get transaction status.', 0, $ex);
        }
    }
}

