<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Properties;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Address;
use App\Models\Checkout;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderArea;
use App\Models\OrderDetails;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function payment(){
        return inertia('Frontend/Customer/Payment',[
            'make_payment' => URL::route('makePayment'),
            'payment_ssl' => URL::route('payWIthSSl'),
            'info' => [
                "status"=>200,
                "message" => 'Checkout Successful, Make Payment Now...'
            ],
            'checkoutData' => Session::get('checkoutData')
        ]);
    }

    public function makePayment(){
        $checkout = Session::get('checkoutData');
        if ($checkout){
            if (Request::input('formData')['paymentMethod'] == 'cod'){
                $order = $this->saveOrder($checkout);
                if ($order){
                    Session::forget('checkoutData');
                    if (Properties::$cartSaveDb) {
                        $checkoutDetails = Checkout::with(['customerCart', 'orderArea'])
                            ->where('id', $checkout["data"]['id'],)->first();
                        $checkoutDetails->customerCart->delete();
                    }

                    return Response::json([
                        "status" => 500,
                        "order" => Order::orderBy('id', 'desc')->first()?->load('customer:id,name','address','orderDetails', 'orderDetails.product') ?? [],
                        "url" => URL::route('orderComplete'),
                        'message' => "Order Complete. Thanks For Your Order."
                    ]);

                }else{
                    return Response::json([
                        "status" => 500,
                        "url" => URL::route('checkout'),
                        'message' => "Checkout Details Not Found."
                    ]);
                }
            }
            else{
                $order = $this->saveOrder($checkout);
                if ($order){
                    $res =$this->orderSSLPHP($order, $checkout);
                    if ($res){
                        return Response::json([
                            "status" => 200,
                            "data" => $res,
                        ]);
                    }else{
                        return Response::json([
                            "status" => 500,
                            "url" => URL::route('payment'),
                            'message' => "SSLCommerz connection failed. Contact Administrator..."
                        ]);
                    }

                }else{
                    return inertia('Frontend/Customer/Conformation',[
                        'info' => [
                            "status"=>200,
                            "message" => 'Order Failed... Try Again.'
                        ]
                    ]);
                }
            }
        }else{
            return Response::json([
                "status" => 500,
                "url" => URL::route('checkout')
            ]);
        }
    }

    private function saveOrder($checkout){
        $cartItems = [];
        if (Properties::$cartSaveDb) {
            $checkoutDetails = Checkout::with(['customerCart', 'orderArea'])
                ->where('id', $checkout["data"]['id'],)->first();
            $cartItems = json_decode($checkoutDetails->customerCart->cartItems);
        } else {
            $cartItems = json_decode($checkout['data']['customer_cart_id']['cartItems']);
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            "order_area_id" => $checkout['data']['order_area_id'],
            "cart_total_price" => $checkout['data']['cart_total_price'],
            "delivery_charge" => $checkout['data']['delivery_charge'],
            "coupon_discount" => $checkout['data']['coupon_discount'],
            "grand_total" => $checkout['data']['grand_total'],
            'order_date' => now()->format('d-m-y'),
            'payment_method' => Request::input('formData')['paymentMethod'],
            'order_status' => 'pending',
            'payment_status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderDetails::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->quantity,
                'buy_size' => $item->size ?? null,
            ]);
        }
        return $order;
    }


    public function orderComplete(){
        return inertia('Frontend/Customer/Conformation',[
            "order" => Order::orderBy('id', 'desc')->first()?->load('customer:id,name','address','orderDetails', 'orderDetails.product'),
            'info' => [
                "status"=>200,
                "message" => 'Checkout Successful, Make Payment Now...'
            ]
        ]);
    }

    private function orderSSLPHP($order, $checkout)
    {
        $checkoutDetails = Address::findOrFail($checkout["data"]['delivery_address_id']);

        /* PHP */
        $post_data = array();
        $post_data['total_amount'] = $order->grand_total;
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = Auth::user()?->name;
        $post_data['cus_email'] = Auth::user()?->email;
        $post_data['cus_add1'] = $checkoutDetails?->address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = Auth::user()?->phone;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = $checkoutDetails?->title;
        $post_data['ship_add1'] = $checkoutDetails->address;
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = $checkoutDetails->phone;
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        $sslc = new SslCommerzNotification();
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');
        $res = json_decode($payment_options);

//        return dd(Auth::user());
        if ($res->status == 'fail'){
            return false;
        }else{
            $order->transaction_id = $post_data['tran_id'];
            $order->status = "Pending";
            $order->currency = $post_data['currency'];
            $order->save();
//            Session::forget('checkoutData');
//            if (Properties::$cartSaveDb) {
//                $checkoutDetails = Checkout::with(['customerCart', 'orderArea'])
//                    ->where('id', $checkout["data"]['id'],)->first();
//                $checkoutDetails->customerCart->delete();
//            }
            return $res;
        }


    }

    public function success()
    {
        $tran_id = Request::input('tran_id');
        $amount = Request::input('amount');
        $currency = Request::input('currency');
        $sslc = new SslCommerzNotification();
        #Check order status in order tabel against the transaction id or order id.
        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'grand_total')->first();

        if ($order_details->status == 'Pending') {

            $validation = $sslc->orderValidate(Request::all(), $tran_id, $amount, $currency);
            if ($validation) {
                $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Processing', 'payment_status' => 'paid']);
                return inertia('Frontend/Customer/Conformation',[
                    "order" => Order::orderBy('id', 'desc')->first()?->load('customer:id,name','address','orderDetails', 'orderDetails.product'),
                    'info' => [
                        "status"=>200,
                        "message" => 'Checkout Successful, Make Payment Now...'
                    ]
                ]);
            }
        } else {
            return Redirect::route('payment')->with(['message' =>'Transaction Not Valid.']);
//            return inertia('Frontend/Customer/Payment',[
//                'make_payment' => URL::route('makePayment'),
//                'payment_ssl' => URL::route('payWIthSSl'),
//                'info' => [
//                    "status"=>200,
//                    "message" => 'Checkout Successful, Make Payment Now...'
//                ],
//                'checkoutData' => Session::get('checkoutData')
//            ]);
        }
    }

}
