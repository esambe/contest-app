<?php

namespace App\Http\Controllers;

use App\Contest;
use App\Contestant;
use App\Custom\MomoMtn;
use App\Custom\MomoOrange;
use App\OrangeMomoTransaction;
use Illuminate\Http\Request;
use App\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// use Bmatovu\MtnMomo\Exceptions\CollectionRequestException;
// use Bmatovu\MtnMomo\Products\Collection;


class PaymentController extends Controller
{


    public function vote(Request $request)
    {
        if($request->payment_method == 'mtn') {

            $request->validate([
                'number' => 'required|numeric'
            ]);

            $contest = Contest::where('id', '=', $request->contest_id)->first();

            $voting_charge = $contest->voter_charge;
            $payer_message = 'VOTE CHARGE FROM E-CONTEST';
            $payee_note = 'VOTE CHARGE TO PAY';

            $country_code = '237';
            $collection = new MomoMtn();
            $momoTransactionid = $collection->requestToPay($country_code.$request->number, '1', $payer_message, $payee_note);

            $init_trans_status = $collection->getCollectionTransactionStatus($momoTransactionid);
            $current_trans_status = $init_trans_status['status'];

            while($current_trans_status == 'PENDING'){
                $init_trans_status = $collection->getCollectionTransactionStatus($momoTransactionid);
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
                return back()->with('success', 'Payment successful and voted successfully for '. $contestant);

            }
            if($current_trans_status == "FAILED")
            {
                return back()->with('danger', 'Payment Failed. Please try again.');
            }

            // else if($current_trans_status == "TIMEOUT")
            // {
            //     return back()->with('danger', 'Payment timed out. Please try again.');
            // }
            // else if($current_trans_status == "REJECTED")
            // {
            //     return back()->with('danger', 'Payment rejected.');
            // }
            else {
                return back()->with('danger', 'Payment unsuccessful. Unknown error occured');
            }


        }



        if($request->payment_method == 'orange') {

            $contest = Contest::where('id', '=', $request->contest_id)->first();
            $voting_charge = $contest->voter_charge;

            $collection = new MomoOrange();
            // $transaction = $collection->requestToPay($voting_charge, $contest->id, $contest->name);

            // dd($transaction);

            $transaction = $collection->process_payment($voting_charge, $contest->id, $contest->name);

            // dd($transaction);

            $result = $transaction['result'];
            $pay_token = $transaction['pay_token'];
            $notif_token = $transaction['notif_token'];
            $redir_url = $transaction['redirect'];
            $order_id  = $transaction['order_id'];

            // dd($order_id);


            if( $result == 'success' ) {

                $temp_save = new OrangeMomoTransaction;
                // To be saved in db
                $contest_id                 = $request->contest_id;
                $contestant_id              = $request->contestant_id;
                $temp_save->pay_token       = $pay_token;
                $temp_save->notif_token     = $notif_token;
                $temp_save->contest_id      = $contest_id;
                $temp_save->contestant_id   = $contestant_id;
                $temp_save->user_id         = $request->user_id;
                $temp_save->order_id        = $order_id;
                $temp_save->save();
                return redirect($redir_url);
            }

            if($result == 'fail' ) {
                return back()->with('danger', 'Sorry, we were unable to initiate transaction. Please try again.');
            }
            if ($result == 'error') {
                return back()->with('danger', 'You are unauthorized to access the Orange Money Gateway.');
            }
            if($result == 'danger'){
                return back()->with('danger', 'An unexpected error occured. Please try again later');
            }
            return back()->with('danger', 'Cannot vote. Payment method not ready for use. Please try another');
        }

    }


    public function orange_notif() {

        $transaction = OrangeMomoTransaction::where('user_id', '=', Auth::user()->id)
            ->latest()
            ->first();

        // dd($transaction->pay_token);

        $contest = Contest::where('id', '=', $transaction->contest_id)->first();
        // dd($contest->name);
        $voting_charge = $contest->voter_charge;
        $collection = new MomoOrange();

        $check = $collection->checkTransactionStatus($transaction->order_id, $voting_charge, $transaction->pay_token);

        // dd($check);
        if($check->status == 'SUCCESS')
        {

            $vote = new Vote;
            $count = Vote::where('contestant_id', $transaction->contestant_id)->latest()->value('vote_count');
            $contestant = Contestant::where('id', $transaction->contestant_id)->value('name');
            // persist some data in your application
            $vote->contest_id = $transaction->contest_id;
            $vote->contestant_id = $transaction->contestant_id;
            $vote->vote_count = $count + 1;
            $vote->save();
            // dd($_contest->name);
            return redirect('/contest/contestant/'.$contest->id.'-'.Str::slug($contest['name']))->with('success', 'Payment successful and voted successfully for '. $contestant);
        }
        else if ($check->status == 'FAILED')
        {
            return redirect('/contest/contestant/'.$contest->id.'-'.Str::slug($contest->name))->with('danger', 'Payment failed');
        }
    }

}
