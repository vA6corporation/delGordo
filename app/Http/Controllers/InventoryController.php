<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Inventory;
use App\Sale;
use DateTime;

class InventoryController extends Controller
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
        $sales = Sale::whereBetween('created_at', [$sd, $ed])->get();
        $salesId = $sales->map(function($item) {
            return $item->id;
        });
        $inventories = Inventory::with(['product' => function($query) {
                return $query->with('category', 'subCategory');
            }])
            ->with(['sale' => function($query) {
                return $query->with('customer', 'delivery');
            }])
            ->whereIn('sale_id', $salesId)
            ->get();
        return ['inventories' => $inventories];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->inventories as $item) {
            for ($i=0; $i < $item['quantity']; $i++) { 
                $inventory = new Inventory($item);
                $inventory->office_id = session('officeId');
                $inventory->codigo = Str::random(10);
                $inventory->save();
            }
        }
        return NULL;
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
        $inventory = Inventory::find($id);
        $inventory->delete();
    }
}
