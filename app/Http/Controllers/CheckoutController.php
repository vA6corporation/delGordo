<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MercadoPago;
use Log;
use DB;

class CheckoutController extends Controller
{

    const REJECTED = "rejected";
    const APPROVED = "approved";
    const IN_PROCESS = "in_process";
    const PENDING = "pending";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request;
        if($request->isMethod('post')) {
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
                        'msg' => [
                            'Favor de revisar la información de la tarjeta', 
                            [], 
                            false
                        ]
                    ], 400)->header('Content-Type', 'application/json');
                    break;
            }
            //DB::beginTransaction();
            try {
                $user = true;
                if ($user) {
                    // Log::info($isCard);
                    MercadoPago\SDK::setAccessToken('TEST-990840703433865-072809-e62819351af3852a6c9bec3d472486cd-146569502');

                    $items[] = array(
                        "id"          => $request->id_product,
                        "title"       => $request->title_product,
                        "description" => $request->description_product,
                        "quantity"    => (int)number_format($request->quantity, 0, '.', ''),
                        "unit_price"  => (float)number_format($request->transaction_amount, 2, '.', '')
                    );

                    $dateExpiration = date("Y-m-d\TH:i:s.000P", mktime(date("H"), date("i"),date("s"), date("m"), date("d") + 15, date("Y")));
                    $payment = new MercadoPago\Payment();
                    $payment->transaction_amount = (float)number_format($request->transaction_amount, 2, '.', '');
                    $payment->token = $request->token;
                    $payment->description = "Tu empresa";
                    $payment->installments = intval($request->monthly_installments);
                    $payment->payment_method_id = $request->payment_method_id;
                    $payment->date_of_expiration = $dateExpiration;
                    $payment->metadata = array(
                        "products" => $items,
                        "userId" => 1 //debe venir de la bd y de la varible $user
                    );
                    $payment->payer = array(
                        "email" =>  $request->email
                    );
                    $payment->additional_info = array(
                        "items" => $items,
                        "payer" => array(
                            "first_name" => "Demo", //$user->nombre, debe venir de la bd y de la varible $user
                            "last_name" => "Demo", //$user->nombre, debe venir de la bd y de la varible $user
                            "phone" => array(
                                "number" => '0000000000' //$user->celular, debe venir de la bd y de la varible $user
                            )
                        )
                    );

                    $payment->save();
                    $status = $payment->status;
                    error_log($status);
                    $statusResponse = $payment->status_detail;
                    // Log::info($statusResponse);
                    
                    if (empty($status) && empty($statusResponse)) {
                        return Response([
                            'msg' => [
                                'Tarjeta declinada', 
                                [], 
                                $isCard
                            ]
                        ], 200)->header('Content-Type', 'application/json');
                    }

                    if($status == self::APPROVED || $status == self::IN_PROCESS || $status == self::PENDING) {
                        return Response([
                            'msg' => [
                                $this->_errorMP($statusResponse), 
                                $payment, $isCard
                            ]
                        ], 200)->header('Content-Type', 'application/json');
                    } else if ($status == self::REJECTED) {
                        error_log('pichula');
                        return Response([
                            'msg' => [
                                $this->_errorMP($statusResponse), 
                                $payment, 
                                false
                            ] 
                        ], 200)->header('Content-Type', 'application/json');
                    }
                } else {
                    return Response([
                        'msg' => ['Usuario no registrado.']
                    ], 400)->header('Content-Type', 'application/json');
                }
            } catch (\Exception  $e) {
                //DB::rollback();
                Log::error('Error: '.$e->getMessage().' - Line:' .$e->getLine() . ' - Archivo: ' . $e->getFile());
                return Response(['error' => [$e->getMessage(). $e->getLine()]], 400)->header('Content-Type', 'application/json');
            }
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