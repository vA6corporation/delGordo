<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Sale;
use App\Payment;
use App\Customer;
use App\Inventory;
use Illuminate\Support\Collection;
use DateTime;
use Illuminate\Support\Facades\Mail;
use App\Mail\SaleNotifyCustomer;
use App\Mail\SaleNotifyPaymentCustomer;
use App\Mail\SaleNotify;
use MercadoPago;
use App;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    const REJECTED = "rejected";
    const APPROVED = "approved";
    const IN_PROCESS = "in_process";
    const PENDING = "pending";
    protected $token = NULL;

    public function __construct()
    {
        if (App::environment('production')) {
            $this->token = env('TOKEN');
        } else {
            $this->token = env('TEST_TOKEN');
        }
    }

    public function index(Request $request)
    {
        $sd = (new DateTime($request->sd))->format('Y-m-d');
        $ed = (new DateTime($request->ed))->modify('+1 day')->format('Y-m-d');
        $query = Sale::withTrashed()
            ->with('customer', 'items', 'delivery', 'deliveryman')
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
        $sales = $query->paginate(18);
        return [
            'sales' => $sales->items(),
            'count' => $sales->total(),
            'pages' => $sales->lastPage(),
        ];
    }

    public function all(Request $request)
    {
        $sd = (new DateTime($request->sd))->format('Y-m-d');
        $ed = (new DateTime($request->ed))->modify('+1 day')->format('Y-m-d');
        $query = Sale::withTrashed()
            ->with('customer', 'items', 'delivery', 'deliveryman', 'user')
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
        $sales = $query->get();
        return [
            'sales' => $sales,
            // 'count' => $sales->total(),
            // 'pages' => $sales->lastPage(),
        ];
    }

    public function dispatchedSales(Request $request)
    {
        $sd = (new DateTime($request->sd))->format('Y-m-d');
        $ed = (new DateTime($request->ed))->modify('+1 day')->format('Y-m-d');
        $query = Sale::withTrashed()
            ->with('customer', 'items', 'delivery', 'deliveryman')
            ->whereBetween('created_at', [$sd, $ed]);
        $query->whereNotNull('deliver_date');
        $query->whereNull('delivered_date');
        $sales = $query->paginate(18);
        return [
            'sales' => $sales->items(),
            'count' => $sales->total(),
            'pages' => $sales->lastPage(),
        ];
    }

    public function deliverySales(Request $request)
    {
        $sd = (new DateTime($request->sd))->format('Y-m-d');
        $ed = (new DateTime($request->ed))->modify('+1 day')->format('Y-m-d');
        $query = Sale::withTrashed()
            ->with('customer', 'items', 'delivery', 'deliveryman')
            ->whereBetween('created_at', [$sd, $ed]);
        $query->whereNotNull('dispatched_date');
        $query->whereNotNull('deliver_date');
        $query->whereNull('delivered_date');
        $sales = $query->paginate(18);
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

    public function pay(Request $request) {

        $sale = Sale::find($request->sale['id']);
        $payment = new Payment([
            // 'date_created' => (new DateTime())->format('Y-m-d H:i:s'),
        ]);
        $payment->user_id = Auth::id();
        $payment->save();
        $sale->payment_id = $payment->id;
        $sale->save();
        return ['ok' => true];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email = $request->email;
        $processPayment = $request->sale['processPayment'];
        $deliver_date = $request->sale['deliver_date'];
        $sale = new Sale($request->sale);
        if ($deliver_date == 'true') {
            $sale->deliver_date = (new DateTime())->format('Y-m-d H:i:s');
        } else {
            $sale->deliver_date = NULL;
        }
        if ($sale->payment_id) {
            $payment = new Payment([
                // 'date_created' => (new DateTime())->format('Y-m-d H:i:s'),
            ]);
            $payment->user_id = Auth::id();
            $payment->save();
            $sale->payment_id = $payment->id;
        }

        $ids = collect($request->inventories)->map(function($item) {
            return $item['id'];
        });
        $inventories = Inventory::whereIn('id', $ids)->with('product')->get();

        $check = $inventories->search(function($item, $key) {
            return $item->sale_id != NULL;
        });
        if ($check == false) {
            $sale->user_id = Auth::id();
            $sale->save();
            foreach ($inventories as $inventory) {
                $inventory->sale_id = $sale->id;
                $inventory->sale_price = $inventory->product->sale_price;
                $inventory->save();
            }
            try {
                if ($processPayment) {
                    Mail::to($email)->send(new SaleNotifyPaymentCustomer(['id' => $sale->id]));
                } else {
                    Mail::to($email)->send(new SaleNotifyCustomer(['id' => $sale->id]));
                }
                Mail::to(env('MAIL_USERNAME'), 'contacto@delgordo.com.pe')->send(new SaleNotify(['id' => $sale->id]));
            } catch (\Throwable $th) {
                error_log('No se pudo enviar email');
            }
            return ['sale' => $sale];
        } else {
            return response(400, 'Revise la disponibilidad del inventario');
        }
    }

    public function shopPayment(Request $request)
    {
        return $request;
        $payment_method_id = $request->paymentMethodId;
        $transaction_amount = $request->transactionAmount;
        $description = $request->description;
        $installments = $request->installments;
        $email = $request->email;
        $first_name = $request->cardholderName;
        $doc_type = $request->docType;
        $doc_number = $request->docNumber;
        $token = $request->token;
        
        try {
            $mercadopago_payment = $this->checkout(
                $payment_method_id, 
                $transaction_amount, 
                $description,
                $installments,
                $doc_type,
                $doc_number,
                $email,
                $token 
            );
        } catch (\Throwable $th) {
            return response(['message' => 'Favor de revisar la información de la tarjeta'], 400);
        }

        $sale = Sale::find($request->sale['id']);
        $payment = new Payment();
        $payment->mercadopago_id = $mercadopago_payment->id;
        $payment->save();
        $sale->payment_id = $payment->id;
        $sale->save();

        return ['sale' => $sale];
    }

    public function shop(Request $request)
    {
        // return $request;
        $payment_method_id = $request->paymentMethodId;
        $transaction_amount = $request->transactionAmount;
        $description = $request->description;
        $installments = $request->installments;
        $email = $request->email;
        $first_name = $request->cardholderName;
        $doc_type = $request->docType;
        $doc_number = $request->docNumber;
        $token = $request->token;
        
        $inventories = $request->inventories;
        
        $ids = collect($inventories)->map(function($item) {
            return $item['id'];
        });
        
        $inventories = Inventory::whereIn('id', $ids)->with('product')->get();
        
        $check = $inventories->search(function($item, $key) {
            return $item->sale_id != NULL;
        });

        if ($check) {
            return response(['message' => 'Revise la disponibilidad del inventario'], 400);
        }

        try {
            $mercadopago_payment = $this->checkout(
                $payment_method_id, 
                $transaction_amount, 
                $description,
                $installments,
                $doc_type,
                $doc_number,
                $email,
                $token 
            );
        } catch (\Throwable $th) {
            return response(['message' => 'Favor de revisar la información de la tarjeta'], 400);
        }

        $sale = new Sale($request->sale);
        $payment = new Payment();
        $payment->mercadopago_id = $mercadopago_payment->id;
        $payment->save();
        $sale->payment_id = $payment->id;
        $sale->deliver_date = (new DateTime())->format('Y-m-d H:i:s');
        $sale->channel = "TIENDA VIRTUAL";
        $sale->save();

        foreach ($inventories as $inventory) {
            $inventory->sale_id = $sale->id;
            $inventory->sale_price = $inventory->product->sale_price;
            $inventory->save();
        }

        try {
            if (isSet($request->processPayment) && $request->processPayment == true) {
                Mail::to($email)->send(new SaleNotifyPaymentCustomer(['id' => $sale->id]));
            } else {
                Mail::to($email)->send(new SaleNotifyCustomer(['id' => $sale->id]));
            }
            Mail::to(env('MAIL_USERNAME'), 'contacto@delgordo.com.pe')->send(new SaleNotify(['id' => $sale->id]));
        } catch (\Throwable $th) {
            error_log('No se pudo enviar email');
        }

        return ['sale' => $sale];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::with('customer', 'delivery', 'user', 'deliveryman')
            ->with(['items' => function($query) {
                return $query->with('product');
            }])
            ->with(['payment' => function($query) {
                return $query->with('user');
            }])
            ->find($id);
        $items = collect($sale->items)->groupBy('product_id');
        return [
            'sale' => $sale,
            'items' => $items,
        ];
    }

    public function delivery($id)
    {
        $sale = Sale::with('items')->find($id);
        foreach ($sale->items as $item) {
            $inventory = Inventory::find($item->id);
            $inventory->fill([
                'delivered_date' => (new DateTime())->format('Y-m-d H:i:s'),
            ]); 
            $inventory->save();
        }
        $sale->fill([
            'delivered_date' => (new DateTime())->format('Y-m-d H:i:s'),
        ]);
        $sale->save();
        return ['sale' => $sale];
    }

    public function dispatched($id)
    {
        $sale = Sale::with('items')->find($id);
        foreach ($sale->items as $item) {
            $inventory = Inventory::find($item->id);
            $inventory->fill([
                'dispatched_date' => (new DateTime())->format('Y-m-d H:i:s'),
            ]); 
            $inventory->save();
        }
        $sale->fill([
            'dispatched_date' => (new DateTime())->format('Y-m-d H:i:s'),
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

    public function updateWithInventory(Request $request, $id)
    {
        // return $request;
        $sale = Sale::find($id);
        $inventories = $request->inventories;
        $preInventories = $request->preInventories;
        foreach ($preInventories as $item) {
            // error_log(json_decode($item));
            foreach ($item as $subItem) {
                // error_log(json_encode($subItem));
                $inventory = Inventory::find($subItem['id']);
                $inventory->sale_id = NULL;
                $inventory->save();
                error_log($inventory);
                // error_log($inventory);
            }
        }
        foreach ($inventories as $item) {
            $inventory = Inventory::find($item['id']);
            $inventory->sale_id = $sale->id;
            $inventory->sale_price = $inventory->product->sale_price;
            $inventory->save();
        }
        $sale->fill($request->sale);
        // $inventories = $request->inventories;
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

    private function checkout(
        $payment_method_id, 
        $transaction_amount, 
        $description,
        $installments,
        $doc_type,
        $doc_number,
        $email,
        $token 
    ) {
        error_log('======');
        error_log($payment_method_id);
        error_log($transaction_amount);
        error_log($token);
        error_log($email);

        $isCard = false;
        switch ($payment_method_id) {
            case "master":
                $isCard = true;
                break;
            case "visa":
                $isCard = true;
                break;
            case "debmaster":
                $isCard = true;
                break;
            case "debvisa":
                $isCard = true;
                break;
            case "card":
                $isCard = true;
                break;
            case "amex":
                $isCard = true;
                break;
            case "mercadopagocard":
                $isCard = true;
                break;
            default:
                throw new Exception('Favor de revisar la información de la tarjeta');
        }

        try {
            MercadoPago\SDK::setAccessToken($this->token);
            $payment = new MercadoPago\Payment();
            $payment->payment_method_id = $payment_method_id;
            $payment->transaction_amount = $transaction_amount;
            $payment->installments = 1;
            $payment->token = $token;
            $payment->description = $description;
            $payer = new MercadoPago\Payer();
            $payer->email = $email;
            $payer->identification = [
                "type" => $doc_type,
                "number" => $doc_number,
            ];
            $payment->payer = $payer;
            $payment->save();
            $status = $payment->status;
            $statusDetail = $payment->status_detail;
            error_log(" === $status === ");
            error_log($statusDetail);
            
            if (empty($status) && empty($statusDetail)) {
                throw new Exception('Tarjeta declinada');
            }

            if($status == self::APPROVED || $status == self::IN_PROCESS || $status == self::PENDING) {
                return $payment;
            } else if ($status == self::REJECTED) {
                throw new Exception($this->_errorMP($statusDetail));
            }

        } catch (\Throwable $th) {
            error_log('Error: '.$e->getMessage().' - Line:' .$e->getLine() . ' - Archivo: ' . $e->getFile());
            throw $th;
        }
    }

    private function _errorMP($status_detail)
    {
        $msg = null;

        switch ($status_detail) {
            case "cc_rejected_call_for_authorize":
                $msg = "Rechazo llamar para autorizar.";
                break;

            case "cc_rejected_insufficient_amount":
                $msg = "Rechazo por monto insuficiente.";
                break;

            case "cc_rejected_bad_filled_security_code":
                $msg = "Rechazo por código de seguridad.";
                break;

            case "cc_rejected_bad_filled_date":
                $msg = "Rechazo por fecha de expiración.";
                break;

            case "cc_rejected_bad_filled_other":
                $msg = "Rechazo por error en formulario.";
                break;

            case "cc_rejected_other_reason":
                $msg = "Rechazo general.";
                break;

            case "there_is_another_email":
                $msg = "Ya existe el correo ingresado, favor de ingresar otro.";
                break;

            case "pending_contingency":
                $msg = "Pago pendiente.";
                break;

            case "select_issuer":
                $msg = "Debe seleccionar un banco de la lista de bancos.";
                break;

            case "The_given_data_failed_to_pass_validation":
                $msg = "Ya existe el correo ingresado, favor de ingresar otro.";
                break;

            case "pending_review_manual":
                $msg = "Estamos procesando el pago.<br>En menos de 2 días hábiles te diremos por e-mail si se acreditó o si necesitamos más información.";
                break;

            case "pending_contingency":
                $msg = "Estamos procesando el pago.<br>En menos de una hora te enviaremos por e-mail el resultado.";
                break;

            case "pending_waiting_payment":
                $msg = "Pago pendiente.";
            break;

            case "no_stock":
                $msg = "Ya no hay producto en stock.";
                break;

            case "time_limit":
                $msg = "Tiempo limite de compra alcanzado";
                break;
            case "accredited":
                $msg = "Pago aprobado";
                break;
        }
        return $msg;
    }
}
