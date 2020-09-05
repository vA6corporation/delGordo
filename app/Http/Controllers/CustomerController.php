<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Customer;
use App\Reniec;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDni($dni)  {
        $response = Http::withHeaders([
            'Authorization' => "Bearer 4bca9d9f3ac0da704301bbd2c8882f45da55ca59be4a5e5c4a918c7d981e780c",
            'Content-Type' => "application/json",
        ])->get("https://apiperu.dev/api/dni/{$dni}");
        $customer = $response->json();
        if ($customer['success']) {
            return $customer['data'];
        } else {
            return NULL;
        }
    }
    
    public function index()
    {
        $customers = Customer::paginate(18);
        // return ['customers' => $customers];
        return [
            'customers' => $customers->items(),
            'count' => $customers->total(),
            'pages' => $customers->lastPage(),
        ];
    }

    public function byDni(Request $request)
    {
        $dni = $request->dni;
        $customer = Customer::where('document', $dni)->first();
        if ($customer) {
            return ['customer' => $customer];
        } else {
            // $doc = Reniec::where('documento', $dni)->first();
            $doc = $this->getDni($dni);
            if ($doc) {
                $customer = new Customer([
                    'name' => "{$doc['nombre_completo']}",
                    'document' => $dni,
                    'address' => '',
                    'email' => '',
                    'reference' => '',
                ]);
                $customer->save();
                return ['customer' => $customer];
            } else {
                return response(400, 'Sin resultados');
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $doc = $request->customer;
        $customer = Customer::where('document', $doc['document'])->first();
        if ($customer) {
            $customer->fill($doc);
        } else {
            $customer = new Customer($request->customer);
        }
        $customer->save();
        return ['customer' => $customer];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
        return ['customer' => $customer];
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
        $customer = Customer::find($id);
        $customer->fill($request->customer);
        $customer->save();
        return ['customers' => $customer];
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
