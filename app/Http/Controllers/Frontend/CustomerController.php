<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderArea;
use App\Models\OrderRefand;
use App\Models\User;
use App\Notifications\VerificationNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function login()
    {
        return inertia("Frontend/Auth/Login", [
            'login_url' => URL::route('customer.checkLogin'),
            'reg_url' => URL::route('customer.checkRegistration')
        ]);
    }

    public function checkLogin()
    {
        $credentials = Request::validate([
            'email' => ['required', 'email'],
//            'password' => ['required'],
        ]);


        $user = User::where('email', Request::input('email'))->first();

        if ($user){
            $user->verification_code = generate_code();
            $user->save();
            Session::put('user', $user);
            Notification::sendNow($user, new VerificationNotification($user));
            return Redirect::route('customer.verifyOtp');
        }else{
            return back()->withErrors("Record Not Found");
        }

//        return inertia('Frontend/Auth/Verify');

//        if (Auth::attempt($credentials)) {
//            Request::session()->regenerate();
//            return Redirect::intended('/dashboard');// route('dashboard');
//        }
//
//        return back()->withErrors([
//            'message' => 'The provided credentials do not match our records.',
//        ]);
    }

    public function resendVerifyCode(){
        return "called here";
        $user = Session::get('user');
        if($user){
            $user->verification_code = generate_code();
            $user->save();
            Session::put('user', $user);
            Notification::send($user, new VerificationNotification($user));
            return Redirect::route('customer.verifyOtp')->with('Code Resend Successfully done...');
        }
    }

    public function verifyOtp()
    {
        return inertia('Frontend/Auth/Verify');
    }

    public function checkVerifyOtp()
    {
        $otp = Request::input("otp");
        $password = Request::input("password");
        $user = Session::get('user');
        if ($password) {
            $credintials = [
              "email" => $user->email,
              "password" => Request::input('password')
            ];
            if (Auth::attempt($credintials)) {
                Session::remove('user');
                Request::session()->regenerate();
                return Redirect::intended('/dashboard');
            }else{
                return back()->withErrors([
                   "message" => "Email Or Password Not Matched."
                ]);
            }
        }else if ($otp) {
            if ($user->verification_code == $otp){
                Auth::login($user);
                Session::remove('user');
                if ($user->password){
                    return Redirect::intended('/dashboard');
                }else{
                    return Redirect::route('gotoGivenPassword');
                }
            }else{
                return back()->withErrors([
                   "message" => "Otp Code Not Matched"
                ]);
            }
        } else {
            return back()->withErrors([
               "message" => "Required Password or OTP Code."
            ]);
        }
    }

    public function gotoGivenPassword(){
        return inertia('Frontend/Auth/GivenPassword');
    }

    public function dashboard()
    {
        return inertia('Frontend/Customer/Dashboard', [
            'orders' => Auth::user()->orders()->with("orderRefand")->latest()->paginate(10),
        ]);
    }

    public function customerOrders()
    {
        return inertia('Frontend/Customer/CustomerOrders', [
            'orders' => Auth::user()->orders()->latest()->paginate(10),
        ]);
    }

    public function customerProfile()
    {
        return inertia('Frontend/Customer/CustomerProfile');

    }

    public function checkRegistration()
    {
        Request::validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users'
        ]);

        $user = User::create([
            'name' => Request::input('username'),
            'email' => Request::input('email'),
            'phone' => Request::input('phone')
        ]);

        if ($user) {
            $user->verification_code = generate_code();
            $user->save();
            Session::remove('user');
            Session::put('user', $user);

            $user->notify(new VerificationNotification($user));


//            Notification::sendNow($user, new VerificationNotification($user));
            return Redirect::route('customer.regVerificationOtp');
        } else {
            return Inertia::render("Frontend/Auth/Login", [
                'login_url' => URL::route('customer.checkLogin'),
                'reg_url' => URL::route('customer.checkRegistration'),
                'errors' => 'User Not Create. have an error.'
            ]);
        }
    }

    public function setPassword(){
        Request::validate([
            'password' => 'min:6|required_with:conform_pas|same:conform_pas',
        ]);
        $user = Auth::user();
        $user->password = Hash::make(Request::input('password'));
        $user->save();
        return Redirect::intended('/dashboard');
    }


    public function regVerificationOtp(){
        return inertia('Frontend/Auth/RegisterVerify');
    }

    public function updateProfile()
    {
        $user = User::findOrFail(Auth::id());
        $user->name = Request::input('name') ?? $user->name;
        $user->email = Request::input('email') ?? $user->email;
        $user->phone = Request::input('phone') ?? $user->phone;
        $user->update();

        return back();
    }

    public function updatePassword()
    {
        $user = Auth::user();
        $hashedPassword = $user->password;

        if (Hash::check(Request::input('current'), $hashedPassword)) {
            if (!Hash::check(Request::input("new"), $hashedPassword)) {
                $user->update([
                    'password' => Hash::make(Request::input("new"))
                ]);
                Auth::logout();
                return redirect()->route('customer.login');
            } else {
                return Inertia::render('Frontend/Customer/Dashboard', [
                    'errors' => 'New Password Can Not Be Same As Same Password',
                    'orders' => Auth::user()->load('orders'),
                ])->with('error', 'New Password Can Not Be Same As Same Password');
            }
        } else {
            if ($user->password == null){
                $user->password = Hash::make(Request::input('password'));
                $user->save();
                return back();
            }


            return Inertia::render('Frontend/Customer/Dashboard', [
                'errors' => 'Current Password Not Match',
                'orders' => Auth::user()->load('orders'),
            ])->with('error', 'New Password Can Not Be Same As Same Password');
        }

        return back();
    }


    public function updateProfileImage()
    {
        $user = User::findOrFail(Auth::id());

        if (Request::hasFile('image')) {
            $icon = Request::file('image')->store('uploads/all', 'public');
//            fileResize(Request::file('image'), $icon, Properties::$productImageWeight, Properties::$productImageHeight);
            $user->avatar = $icon;
            $user->save();
        }
    }

    public function customerAddress(){

        $areas = OrderArea::all();
        $userAddress = Auth::user()->addresses->load('orderArea');


        return inertia('Frontend/Customer/CustomerAddress',[
            'aries' => $areas,
            'addresses' => $userAddress,
            'main_url' => URL::route('addAddress'),
        ]);
    }

    public function addAddress(){

        $data = Request::validate([
           'title' => 'required',
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'area' => 'required',
            'address' => ['required']
        ]);

        $data['order_area_id'] = Request::input('area');
        $data['user_id'] = Auth::id();
        $data['set_default'] = filled(Request::input('isPrimary'));

        Address::create($data);
        return back();

    }
    public function updateAddress($id){
        $data = Request::validate([
           'title' => 'required',
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'area' => 'required',
            'address' => ['required']
        ]);

        $data['order_area_id'] = Request::input('area');
        $data['user_id'] = Auth::id();
        $data['set_default'] = filled(Request::input('isPrimary'));

        $address = Address::findOrFail($id);
        $address->update($data);
        return back();
    }

    public function deleteAddress($id){
        $address = Address::findOrFail($id);
        $address->delete();
        return back();
    }

    public function getCustomerInfo(){
        return Auth::user();
    }



    public function singleOrder($id){
        $order = Order::findOrFail($id)->load('orderDetails', 'customer', 'address', 'orderDetails.product');
        return inertia('Frontend/Customer/OrderInvoice',[
            'order' => $order,
        ]);
    }

    public function printInvoice($id){
        $order = Order::findOrFail($id)->load('orderDetails', 'customer', 'address', 'orderDetails.product');
        return inertia('Order/PrintInvoice', [
            'order' => $order
        ]);
    }

    public function cancelOrder($id){
        $order = Order::findOrFail($id);
        $order->order_status = "cancel";
        $order->save();
        return  back();
    }


    public function saveRefand(){
        $banner = null;
        if (Request::hasFile('image')){
            $banner = Request::file('image')->store('uploads/all', 'public');
        }

        OrderRefand::create([
            'order_id' => Request::input('orderId'),
            'region' => Request::input('region'),
            'about_problem' => Request::input('message'),
            'problem_image' => $banner
        ]);

        return back()->with('message', 'Refund Request Send');

    }


    public function wishlist(){
        return inertia('Frontend/Customer/CustomerWishlist');
    }

    public function refunds(){
         $refunds = Order::with('orderRefand')->whereHas('orderRefand')->get();
        return inertia('Frontend/Customer/Refunds',[
            'refunds' => $refunds
        ]);
    }


}
