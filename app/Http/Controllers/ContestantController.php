<?php

namespace App\Http\Controllers;

use App\Contestant;
use Illuminate\Http\Request;
use Image;

class ContestantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contest.contestants');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contests.contestants.create-contestant');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contestant = new Contestant;

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:contestants',
            'city'  => 'required|string',
            'phone' => 'required|string',
            'description' => 'required|string',
            'contest_id' => 'required',
            'user_img' => 'required|mimes:jpg,jpeg,png,bmp,tiff |max:4096'
        ]);

        if($request->file('user_img')) {
            // Processing Image
            $originalImage  = $request->file('user_img');
            $thumbnailImage = Image::make($originalImage);
            $thumbnailPath  = public_path().'/thumbnail/';
            $originalPath   = public_path().'/uploads/';
            $thumbnailImage->save($originalPath.time().$originalImage->getClientOriginalName());

            $thumbnailImage->resize(150,150);
            $thumbnailImage->save($thumbnailPath.time().$originalImage->getClientOriginalName());
            // End image procesing
            $contestant->user_img = time().$originalImage->getClientOriginalName();
        }

        $contestant->name           = $request->name;
        $contestant->email          = $request->email;
        $contestant->city           = $request->city;
        $contestant->phone          = $request->phone;
        $contestant->description    = $request->description;
        $contestant->contest_id     = $request->contest_id;
        $contestant->save();

        return back()->with('success', 'Contestant added with success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contestant = Contestant::find($id);

        return view('contests.contestants.edit-contestant', compact('contestant'));
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
        $contestant = Contestant::find($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'city'  => 'required|string',
            'phone' => 'required|string',
            'description' => 'required|string',
            'contest_id' => 'required'
        ]);


        if($request->file('user_img')) {
            // Processing Image
            $originalImage  = $request->file('user_img');
            $thumbnailImage = Image::make($originalImage);
            $thumbnailPath  = public_path().'/thumbnail/';
            $originalPath   = public_path().'/uploads/';
            $thumbnailImage->save($originalPath.time().$originalImage->getClientOriginalName());

            $thumbnailImage->resize(150,150);
            $thumbnailImage->save($thumbnailPath.time().$originalImage->getClientOriginalName());
            // End image procesing
            $contestant->user_img = time().$originalImage->getClientOriginalName();
        }

        $contestant->name = $request->name;
        $contestant->email = $request->email;
        $contestant->city = $request->city;
        $contestant->phone = $request->phone;
        $contestant->description = $request->description;
        $contestant->contest_id = $request->contest_id;
        $contestant->save();

        return back()->with('success', 'Contestant updated with success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contestant = Contestant::find($id);

        $contestant->delete();

        return back()->with('danger', 'Contestant deleted with success');
    }
}
