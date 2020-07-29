<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Inventory;
use Illuminate\Support\Collection;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::with('customer', 'items')->get();
        return ['sales' => $sales];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = $request->customer;
        $sale = new Sale([
            'customer_id' => $customer['id'],
        ]);
        $ids = collect($request->inventories)->map(function($item) {
            return $item['id'];
        });
        $inventories = Inventory::whereIn('id', $ids)->with('product')->get();

        $check = $inventories->search(function($item, $key) {
            return $item->sale_id != NULL;
        });
        if ($check == false) {
            $sale->save();
            foreach ($inventories as $inventory) {
                $inventory->sale_id = $sale->id;
                $inventory->sale_price = $inventory->product->sale_price;
                $inventory->save();
            }
            return ['sale' => $sale];
        } else {
            return response(400, 'Revise la disponibilidad del inventario');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::with('customer')->with(['items' => function($query) {
            return $query->with('product');
        }])->find($id);
        return ['sale' => $sale];
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
        //
    }
}
