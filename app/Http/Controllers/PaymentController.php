<?php

namespace App\Http\Controllers;

use App\Contestant;
use Illuminate\Http\Request;
use App\Vote;
use Escarter\Openapimomo\OpenapiMoMo;

class PaymentController extends Controller
{


    public function vote(Request $request)
    {

        if($request->payment_method == 'mtn') {

                $request->validate([
                    'number' => 'required'
                ]);

                $momoapi = new OpenapiMoMo();

                $amount = '200';
                $number = $request->number;
                $payer_message = 'I am the payer';
                $payee_notes = 'I am the payee';

                $trans_id = $momoapi->requestPayment($number, $amount, $payer_message, $payee_notes);

                $init_trans_status = $momoapi->getCollectionTransactionStatus($trans_id);

                $current_trans_status = $init_trans_status['status'];

                /** Note: when a request is made to the requesttopay endpoint its default status on success is 'PENDING' (waiting for user confirmation)
                 * so you might want to write some logic that waits for user's confirmation before you proceed or peform this in the background depending on your application logic.
                 * below is the sample code i use since i need to confirm payment before proceeding to next step in my application(this has it's drawbacks) :(
                 *
                 *    while($current_trans_status == 'PENDING'){
                 *          $init_trans_status = $momoapi->getCollectionTransactionStatus($trans_id);
                 *          $current_trans_status = $init_trans_status['status'];
                 *     }
                */
                while($current_trans_status == 'PENDING'){

                    $init_trans_status = $momoapi->getCollectionTransactionStatus($trans_id);
                    $current_trans_status = $init_trans_status['status'];
                }

                if($current_trans_status == "SUCCESSFUL") {

                    $vote = new Vote;

                    $count = Vote::where('contestant_id', $request->contestant_id)->latest()->value('vote_count');

                    $contestant = Contestant::where('id', $request->contestant_id)->value('name');
                    // persist some data in your application
                    $vote->contest_id = $request->contest_id;
                    $vote->contestant_id = $request->contestant_id;
                    $vote->vote_count = $count + 1;
                    $vote->save();

                    return back()->with('success', 'Payment successfully and voted successfully for '. $contestant);
                } else{
                    // persist some data in your application
                    // return 'to some view with error message!';
                    return back()->with('danger', 'Cannot vote. Payment failed');
                }
        }

        if($request->payment_method == 'orange') {

            $request->validate([
                'number' => 'required'
            ]);
            return back()->with('danger', 'Cannot vote. Payment method not ready for use. Please try another');
        }

    }
}
