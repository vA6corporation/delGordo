<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deliveryman;

class DeliverymanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dealers = Deliveryman::all(); 
        return ['dealers' => $dealers ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deliveryman = new Deliveryman($request->deliveryman);
        $deliveryman->save();
        return ['deliveryman' => $deliveryman];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deliveryman = Deliveryman::find($id);
        return ['deliveryman' => $deliveryman];
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
        $deliveryman = Deliveryman::find($id);
        $deliveryman->fill($request->deliveryman);
        $deliveryman->save();
        return ['deliveryman' => $deliveryman];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
