<?php

namespace App\Http\Controllers;

use App\Contestant;
use Illuminate\Http\Request;
use App\Vote;
use Escarter\OpenapiMoMo\OpenapiMoMo;

class PaymentController extends Controller
{


    public function vote(Request $request)
    {

        $momoapi = new OpenapiMoMo;

        $trans_id = $momoapi->requestPayment('681114379', '1', 'payer_message', 'payee_notes');

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

        if($current_trans_status == "SUCCESSFUL") {
            // persist some data in your application
            return 'to some view with success message!';

        }else{
            // persist some data in your application
            return 'to some view with error message!';
        }

        // $vote = new Vote;

        // $this->validate($request, [

        // ]);

        // $count = Vote::where('contestant_id', $request->contestant_id)->latest()->value('vote_count');

        // $contestant = Contestant::where('id', $request->contestant_id)->value('name');

        // // dd($count);

        // $is_paid_for = true;

        // if($is_paid_for) {

        //     $vote->contest_id = $request->contest_id;
        //     $vote->contestant_id = $request->contestant_id;
        //     $vote->vote_count = $count + 1;
        //     $vote->save();

        //     return back()->with('success', 'Voted successfully for '. $contestant);

        // } else {
        //     return back()->with('danger', 'Cannot vote. Payment not verified');
        // }

    }
}
