<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Disabled;

class DisabledController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disableds = Disabled::where([
            'office_id' => session('officeId'),
        ])->get();
        return ['disableds' => $disableds];
    }

    public function shop()
    {
        $disableds = Disabled::where([
            'office_id' => 1,
        ])->get();
        return ['disableds' => $disableds];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $disabled = new Disabled($request->disabled);
        $disabled->office_id = session('officeId');
        $disabled->save();
        return ['disabled' => $disabled];
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Disabled::destroy($id);
    }
}
