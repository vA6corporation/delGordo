<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::paginate(18);
        return [
            'paymentMethods' => $paymentMethods->items(),
            'count' => $paymentMethods->total(),
            'pages' => $paymentMethods->lastPage(),
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
        $paymentMethod = new PaymentMethod($request->paymentMethod);
        $paymentMethod->save();
        return ['paymentMethod' => $paymentMethod];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paymentMethod = PaymentMethod::find($id);
        return ['paymentMethod' => $paymentMethod];
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
        $paymentMethod = PaymentMethod::find($id);
        $paymentMethod->fill($request->paymentMethod);
        $paymentMethod->save();
        return ['paymentMethod' => $paymentMethod];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::find($id);
        $paymentMethod->delete();
    }
}
