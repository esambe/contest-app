<?php

namespace App\Http\Controllers;

use App\Contest;
use App\Contestant;
use App\User;
use Illuminate\Http\Request;
use Image;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contests = Contest::all();
        $users = User::all();
        return view('dashboard', compact('contests', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contests.create-contest');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contest = new Contest;

        $request->validate([
            'name' => 'required|string',
            'voter_charge' => 'required|numeric',
            'description' => 'required|string',
            'start_date'  => 'required',
            'end_date'  => 'required',
            'img' => 'max:4096',
        ]);

        if($request->file('img')) {
            // Processing Image
            $originalImage  = $request->file('img');
            $thumbnailImage = Image::make($originalImage);
            $thumbnailPath  = public_path().'/thumbnail/';
            $originalPath   = public_path().'/uploads/';
            $thumbnailImage->save($originalPath.time().$originalImage->getClientOriginalName());

            $thumbnailImage->resize(150,150);
            $thumbnailImage->save($thumbnailPath.time().$originalImage->getClientOriginalName());
            // End image procesing
            $contest->img = time().$originalImage->getClientOriginalName();
        }

        $contest->name = $request->name;
        $contest->voter_charge = $request->voter_charge;
        $contest->description = $request->description;
        $contest->start_date = $request->start_date;
        $contest->end_date = $request->end_date;

        $contest->save();

        return back()->with('success', 'Contest added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $single)
    {

        $contest = Contest::where('id', '=', $single->id)->first();

        $contestants = Contestant::all();

        return view('contests.contestants', compact('contest', 'contestants'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest)
    {
        // return $slug;

        $contest = Contest::where('id', '=', $contest->id)->first();

        return view('contests.edit-contest', compact('contest'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contest = Contest::find($id);

        $request->validate([
            'name' => 'required|string',
            'voter_charge' => 'required|numeric',
            'description' => 'required|string',
            'start_date'  => 'required',
            'end_date'  => 'required'
        ]);

        if($request->file('img')) {
            // Processing Image
            $originalImage  = $request->file('img');
            $thumbnailImage = Image::make($originalImage);
            $thumbnailPath  = public_path().'/thumbnail/';
            $originalPath   = public_path().'/uploads/';
            $thumbnailImage->save($originalPath.time().$originalImage->getClientOriginalName());

            $thumbnailImage->resize(150,150);
            $thumbnailImage->save($thumbnailPath.time().$originalImage->getClientOriginalName());
            // End image procesing
            $contest->img = time().$originalImage->getClientOriginalName();
        }

        $contest->name = $request->name;
        $contest->voter_charge = $request->voter_charge;
        $contest->description = $request->description;
        $contest->start_date = $request->start_date;
        $contest->end_date = $request->end_date;

        $contest->save();

        return back()->with('success', $request->name . ' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contest = Contest::find($id);

        $contest->delete();
        return back()->with('danger', $contest->name . ' deleted successfully');
    }
}
