<?php

namespace App\Http\Controllers;

use App\Contestant;
use App\Custom\MomoMtn;
use Illuminate\Http\Request;
use App\Vote;


// use Bmatovu\MtnMomo\Exceptions\CollectionRequestException;
// use Bmatovu\MtnMomo\Products\Collection;


class PaymentController extends Controller
{


    public function vote(Request $request)
    {
        if($request->payment_method == 'mtn') {
            $country_code = '237';
            $collection = new MomoMtn();
            $momoTransactionid = $collection->requestToPay($country_code .'681114379', '1', 'Payment for Vote', 'Payment for vote');

            // dd($momoTransactionid);

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
                return back()->with('success', 'Payment successfully and voted successfully for '. $contestant);

            } else {
                return back()->with('danger', 'Payment unsuccessful');
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
