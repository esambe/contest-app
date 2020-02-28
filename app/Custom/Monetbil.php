<?php
namespace App\Custom;
use Illuminate\Support\Facades\Log;
/**
* Mobile money transaction class
*/
class Monetbil
{
	private $service;
	private $secret;
	public $phonenumber;
	public $amount;
	public $paymentId;

	public $placepayment_url;
	public $checkpayment_url;
	public $notify_url;
	public $payout_url;

	function __construct()
	{
		// Initialise the email and password (auth) for momo
		$this->service = env('MONETBIL_SERVICE_KEY');
		$this->secret = env('MONETBIL_SERVICE_SECRET');
		$this->placepayment_url = env('MONETBIL_PLACEPAYMENT_URL');
		$this->checkpayment_url = env('MONETBIL_CHECKPAYMENT_URL');
		$this->notify_url = env('MONETBIL_NOTIFY_URL');
		$this->payout_url = env('MONETBIL_PAYOUT_URL');
	}

	public function rules($type)
	{
		$rules = [];
		switch ($type) {
			case 'placePayment':
				$rules = ['amount' => 'numeric', 'phonenumber' => 'integer'];
				break;
			default:
				die('rule type not found. Please use either checkout or payout');
				break;
		}

		return $rules;
	}

	public function setAmount($amount)
	{
		$this->amount = $amount;
	}

	public function setPhoneNumber($phonenumber)
	{
		$this->phonenumber = $phonenumber;
	}

	public function setPaymentId($paymentId)
	{
		$this->paymentId = $paymentId;
	}

	public function setPaymentRef($payment_ref)
	{
		$this->payment_ref = $payment_ref;
	}

	public function getPaymentRef($payment_ref)
	{
		return $payment_ref;
	}

	/**
	 * Charging a user to pay for a service (reservation, visit, map, contact, ...)
	 */
	public function placePayment()
	{
		$data = array(
			'service' 	=> $this->service,
			'phonenumber' 	=> $this->phonenumber ,
			'amount' 	=> $this->amount ,
			'notify_url' 	=> $this->notify_url ,
			'payment_ref' => $this->payment_ref
        );

        // dd($data);

		$response = array('status' => null, 'message' => 'Transaction was never made');

		/*Transaction here*/
		// dd([$data, $this->placepayment_url]);
		$curl = curl_init($this->placepayment_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            return array(
				'status' => null,
				'message' => 'error occured during curl exec. Additioanl info: ' . curl_error($curl) . ' -- ' . json_encode($info),
			);
        }
        curl_close($curl);
        $response = json_decode($curl_response, TRUE);

        if ($response['status'] != 'REQUEST_ACCEPTED') {
        	Log::info([$this->payment_ref, $response]);
        	$message = $response['message'];
        	$status = $response['status'];
        	return array(
				'status' => null,
				'message' => 'Transaction failed with reason "' . $message . '"',
			);
        }
        dd($response);
        return $response;
	}


	public function checkPayment($value='')
	{
		$data = array(
			'paymentId' 	=> $this->paymentId,
			'service' 		=> $this->service,
		);

		$response = array('success' => false,'message' => 'Transaction was never made');

		/*Transaction here*/
		$curl = curl_init($this->checkpayment_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            return array(
				'success' => false,
				'message' => 'error occured during curl exec. Additioanl info: ' . curl_error($curl) . ' -- ' . json_encode($info),
			);
        }
        curl_close($curl);
        $response = json_decode($curl_response, true);

        if (is_array($response) and array_key_exists('transaction', $response)) {
			$transaction = $response['transaction'];
			$status = $transaction['status'];
			if ($status == 1) {
				$response['success'] = true;
				return $response;
			} elseif ($status == - 1) {
				return array('success' => false,'message' => $response['message']);
			} else {
				return array('success' => false,'message' => $response['message']);
			}
		}else {
			return array('success' => false, 'message' => 'Transaction failed. Unknown reason');
		}
	}

	/**
	 * Sending money from the online account to another account (paying the owner of the listing)
	 */
	public function payout()
	{
		$data = array(
			'email' 	=> $this->email ,
			'password' 	=> $this->password ,
			'amount' 	=> $this->amount ,
			'phone' 	=> $this->phonenumber ,
		);


		$response = array(
			'status' => null,
			'message' => 'Transaction was never made',
		);


		/*Transaction here*/
		$curl = curl_init($this->payout_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            return array(
				'status' => null,
				'message' => 'error occured during curl exec. Additioanl info: ' . var_export($info),
			);
        }
        curl_close($curl);
        $response = json_decode($curl_response);
        return $response;
	}
}
