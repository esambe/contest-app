<?php

namespace App\Http\Controllers;

use App\Contestant;
use Illuminate\Http\Request;
use App\Vote;


use Bmatovu\MtnMomo\Exceptions\CollectionRequestException;
use Bmatovu\MtnMomo\Products\Collection;

class PaymentController extends Controller
{


    public function vote(Request $request)
    {
        if($request->payment_method == 'mtn') {

                $request->validate([
                    'number' => 'required'
                ]);

                try {
                    $collection = new Collection();
                    $momoTransactionId = $collection->transact('transactionId', $request->number, 1);

                    if($momoTransactionId) {
                        $vote = new Vote;
                        $count = Vote::where('contestant_id', $request->contestant_id)->latest()->value('vote_count');

                        $contestant = Contestant::where('id', $request->contestant_id)->value('name');
                        // persist some data in your application
                        $vote->contest_id = $request->contest_id;
                        $vote->contestant_id = $request->contestant_id;
                        $vote->vote_count = $count + 1;
                        $vote->save();
                        return back()->with('success', 'Payment successfully and voted successfully for '. $contestant);
                    }

                } catch(CollectionRequestException $e) {
                    do {
                        printf("\n\r%s:%d %s (%d) [%s]\n\r",
                            $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
                    } while($e = $e->getPrevious());
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
