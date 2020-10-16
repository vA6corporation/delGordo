<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = Delivery::paginate(18);
        // return ['deliveries' => $deliveries];
        return [
            'deliveries' => $deliveries->items(),
            'count' => $deliveries->total(),
            'pages' => $deliveries->lastPage(),
        ];
    }

    public function all()
    {
        $deliveries = Delivery::all();
        return [
            'deliveries' => $deliveries,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $delivery = new Delivery($request->delivery);
        $delivery->save();
        return ['delivery' => $delivery];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $delivery = Delivery::find($id);
        return ['delivery' => $delivery];
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
        $delivery = Delivery::find($id);
        $delivery->fill($request->delivery);
        $delivery->save();
        return ['delivery' => $delivery];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delivery = Delivery::find($id);
        $delivery->delete();
    }
}
