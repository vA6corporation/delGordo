<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category', 'subCategory')->paginate(18);
        return [
            'products' => $products->items(),
            'count' => $products->total(),
            'pages' => $products->lastPage(),
        ];
    }

    public function all()
    {
        $products = Product::with('category', 'subCategory')->get();
        return [
            'products' => $products,
        ];
    }

    public function search($key)
    {
        $products = Product::with('category', 'subCategory')->where('name', 'like', "%{$key}%")->get();
        if (count($products)) {
            return ['products' => $products];
        } else {
            return response('Sin resultados', 400);
        }
    }

    public function withInventory()
    {
        $products = Product::with('category', 'subCategory')->paginate(18);
        return [
            'products' => $products->items(),
            'count' => $products->total(),
            'pages' => $products->lastPage(),
        ];
    }

    // public function withInventoryAll()
    // {
    //     $products = Product::with('category', 'subCategory')->get();
    //     return [ 'products' => $products ];
    // }

    public function checkInventory()
    {
        $products = Product::with('category', 'subCategory')->get();
        return ['products' => $products];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product($request->product);
        $product->save();
        return ['product' => $product];
    }

    public function storeImage(Request $request)
    {
        $image_url = $request->image_url;
        if (isSet($image_url)) {
            Storage::delete($image_url);
        }
        $path = $request->file('image')->store('images');
        return $path;
    }

    public function getImage($id)
    {
        return Storage::download("images/$id");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category', 'subCategory')->find($id);
        return ['product' => $product];
    }

    public function inventoryAll($id)
    {
        $product = Product::with('category', 'subCategory', 'inventoryAll')->find($id);
        return ['product' => $product];
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
        $product = Product::find($id);
        $product->fill($request->product);
        $product->save();
        return ['product' => $product];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
    }
}
