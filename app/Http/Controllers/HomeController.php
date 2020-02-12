<?php

namespace App\Http\Controllers;

use App\Contest;
use App\Contestant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $contests = Contest::paginate(10);
        return view('home', compact('contests'));
    }

    public function singleContest($id) {

        $contest = Contest::find($id);

        $contestants = Contestant::where('contest_id', $id)->paginate(10);

        return view('contests.contestants.single-contest', compact('contestants', 'contest'));
    }
}
