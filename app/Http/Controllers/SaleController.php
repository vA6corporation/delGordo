<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Customer;
use App\Inventory;
use Illuminate\Support\Collection;
use DateTime;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sd = (new DateTime($request->sd))->format('Y-m-d');
        $ed = (new DateTime($request->ed))->modify('+1 day')->format('Y-m-d');
        $query = Sale::withTrashed()
            ->with('customer', 'items')
            ->whereBetween('created_at', [$sd, $ed]);
        if ($request->payed == 'true') {
            $query->whereNotNull('payment_id');
        }
        if ($request->payed == 'false') {
            $query->whereNull('payment_id');
        }
        if ($request->delivered == 'true') {
            $query->whereNotNull('delivered_date');
        }
        if ($request->delivered == 'false') {
            $query->whereNull('delivered_date');
        }
        if ($request->deleted == 'true') {
            $query->whereNotNull('deleted_at');
        }
        if ($request->deleted == 'false') {
            $query->whereNull('deleted_at');
        }
        $sales = $query->paginate(10);
        return [
            'sales' => $sales->items(),
            'count' => $sales->total(),
            'pages' => $sales->lastPage(),
        ];
    }

    public function find(Request $request) {
        $key = $request->key;
        $customers = Customer::where('name', 'like', "{$key}%")
            ->orWhere('document', $key)
            ->get();
            $sales = Sale::withTrashed()
            ->whereIn('customer_id', $customers)
            ->orWhere('id', $key)
            ->with('customer', 'items')    
            ->get();
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
        // $customer = $request->customer;
        $sale = new Sale($request->sale);
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
        $sale = Sale::with('customer', 'delivery')->with(['items' => function($query) {
            return $query->with('product');
        }])->find($id);
        return ['sale' => $sale];
    }

    public function delivery($id)
    {
        $sale = Sale::with('items')->find($id);
        foreach ($sale->items as $item) {
            $inventory = Inventory::find($item->id);
            $inventory->fill([
                'delivered_date' => new Datetime(),
            ]); 
            $inventory->save();
        }
        $sale->fill([
            'delivered_date' => new Datetime(),
        ]);
        $sale->save();
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
        $sale = Sale::find($id);
        $sale->fill($request->sale);
        $sale->save();
        return ['sale' => $sale];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSale(Request $request)
    {
        $doc = $request->sale;
        $sale = Sale::with('items')->find($doc['id']);
        foreach ($sale->items as $item) {
            $inventory = Inventory::find($item->id);
            $inventory->fill([
                'sale_id' => NULL,
            ]); 
            $inventory->save();
        }
        $sale->fill($doc);
        $sale->save();
        $sale->delete();
    }
    public function destroy($id)
    {
        //
    }
}
