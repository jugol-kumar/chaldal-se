<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Omnipay\Omnipay;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{

    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(env('PAYPAL_TEST_MODE', true)); //set it to 'false' when go live
    }



    public function payment(Order $order){
        try {
            $response = $this->gateway->purchase(array(
                'amount' => $order->grand_total,
                'items' => array(
                    array(
                        'name' => 'All Products',
                        'price' => $order->grand_total,
                        'description' => 'All Products with delivery charge',
                        'quantity' => 1
                    ),
                ),
                'currency' => env('PAYPAL_CURRENCY', 'USD'),
                'returnUrl' => URL::route('paypal_success'),
                'cancelUrl' => URL::route('paypal_cancel'),
            ))->send();

            return $response->getData();

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function success(Request $request){

        // Once the transaction has been approved, we need to complete it.
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                // The customer has successfully paid.
                $arr_body = $response->getData();

                $update_product = DB::table('orders')
                    ->where('transaction_id', $arr_body["id"])
                    ->update(['status' => 'Processing', 'payment_status' => 'paid']);

                    return inertia('Frontend/Customer/Conformation',[
                        "order" => Order::orderBy('id', 'desc')->first()?->load('customer:id,name','address','orderDetails', 'orderDetails.product'),
                        'info' => [
                            "status"=>200,
                            "message" => 'Checkout Successful, Make Payment Now...'
                        ]
                    ]);

            } else {
                return $response->getMessage();
            }
        } else {
            return 'Transaction is declined';
        }
    }

    public function cancel(){
        return "cancel Payment";
    }
}
