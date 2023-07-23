<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Promo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PromoController extends Controller
{
    public function index(){

        return inertia('Promo/Index', [
            "promos" => Promo::withCount('products')->get(),
            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('admin.coupon.index'),
        ]);
    }


    public function create(){
        return inertia('Promo/Create', [
            'products' => Product::all(),
            'save_url' => URL::route('admin.promos.create'),
        ]);
    }

    public function store(){
        $products = [];
        foreach(Request::input('products') as $item){
            $products[] =[
                'product_id' => $item["id"],
                'discount' => $item["fdiscount"],
                "discount_type" => $item["fdicounttype"]
            ];
        }

        $banner = null;
        if (Request::file('dells')["banner"]){
            $banner = Request::file('dells')["banner"]->store('uploads/all', 'public');
        }

        $promo = Promo::create([
            'title' => Request::input('dells')["title"],
            'banner' => $banner,
            'start_date' => Request::input('dells')['start_date'],
            'end_date' => Request::input('dells')['end_date'],
            "products" => json_encode($products),
        ]);

        $promo->products()->attach($products);

        return back();

    }

    public function show($id){
        $promo = Promo::with('products')->findOrFail($id);



        return inertia('Promo/Edit', [
            'promo' =>$promo,
            'products' => Product::all(),
            'update_url' => URL::route('admin.promos.update', $id)
        ]);
    }


    public function update($id){
        $promo = Promo::findOrFail($id);
        if (isset(Request::file('dells')["banner"])){
            Storage::disk('public')->delete($promo->banner);
            $banner = Request::file('dells')["banner"]->store('uploads/all', 'public');
            $promo->banner = $banner;
        }

        $promo->title = Request::input('dells')['title'];
        $promo->start_date = Request::input('dells')['start_date'];
        $promo->end_date = Request::input('dells')['end_date'];
        $promo->save();

        if(Request::input('products')){
            $products = [];
            foreach(Request::input('products') as $item){
                $products[] =[
                    'product_id' => $item["id"],
                    'discount' => $item["fdiscount"],
                    "discount_type" => $item["fdicounttype"]
                ];
            }
            $promo->products()->sync($products);
        }

        return Redirect::route('admin.promos');
    }


}
