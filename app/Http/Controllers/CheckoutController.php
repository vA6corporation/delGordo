<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MercadoPago;
use App\Sale;
use Illuminate\Support\Facades\Mail;
use App\Mail\SaleNotifyCustomer;
use App\Mail\SaleNotify;

class CheckoutController extends Controller
{

    const REJECTED = "rejected";
    const APPROVED = "approved";
    const IN_PROCESS = "in_process";
    const PENDING = "pending";
    protected $token = NULL;
    // const TOKEN = "10";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->token = env('APP_TOKEN', 'TEST-990840703433865-072809-e62819351af3852a6c9bec3d472486cd-146569502');
    }

    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isCard = false;
        switch ($request->payment_method_id) {
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
                return Response([
                    'msg' => [ 'Favor de revisar la información de la tarjeta', [], false ]
                ], 400);
        }

        try {
            MercadoPago\SDK::setAccessToken($this->token);
            $payment = new MercadoPago\Payment();
            $payment->payment_method_id = $request->payment_method_id;
            $payment->transaction_amount = $request->transaction_amount;
            $payment->token = $request->token;
            $payment->description = "Tienda del gordo";
            $payment->installments = 1;
            $payment->payer = [
                "email" => $request->email,
            ];
            $payment->save();
            $status = $payment->status;
            error_log(" === $status === ");
            $statusResponse = $payment->status_detail;
            error_log($statusResponse);
            
            if (empty($status) && empty($statusResponse)) {
                return Response([
                    'msg' => [ 'Tarjeta declinada', [], $isCard ],
                ], 400);
            }

            if($status == self::APPROVED || $status == self::IN_PROCESS || $status == self::PENDING) {
                $sale = Sale::find($request->sale['id']);
                $sale->fill([
                    'payment_method_id' => $request->payment_method_id,
                    'payment_id' => $payment->id,
                    'on_model' => 'mercadopago',
                ]);
                $sale->save();
                return [
                    'msg' => [ $this->_errorMP($statusResponse), $payment, $isCard ]
                ];
            } else if ($status == self::REJECTED) {
                return Response([
                    'msg' => [ $this->_errorMP($statusResponse), $payment, false ] 
                ], 400);
            }

        } catch (\Exception  $e) {
            error_log('Error: '.$e->getMessage().' - Line:' .$e->getLine() . ' - Archivo: ' . $e->getFile());
            return Response([
                'error' => [ $e->getMessage(). $e->getLine() ]
            ], 400);
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
        //
    }

    /**
     * Search error MP
     * @return msg
     */
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