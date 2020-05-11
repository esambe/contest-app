<?php

namespace App\Http\Controllers;

use App\Contest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //$this->middleware('auth');
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function makeFree($id) {

        $contest = Contest::find($id);
        $contest->is_free = 1;
        $contest->voter_charge = 0;
        $contest->save();
        return back()->with('success', $contest->name . ' contest has been made free' );
    }

    public function makePaid($id) {
        $contest = Contest::find($id);
        $contest->is_free = 0;
        $contest->voter_charge = 1; // Default which can be edited
        $contest->save();
        return back()->with('success', $contest->name . ' contest has been made paid' );
    }
}
