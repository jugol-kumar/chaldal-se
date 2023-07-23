<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Properties;
use App\Models\ActiveColor;
use App\Models\Advised;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Coupon;
use App\Models\Page;
use App\Models\Product;
use App\Models\Promo;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;

class HomeController extends Controller
{
    public function home()
    {
//        $category = json_decode(get_setting('categoryId'));
//        $category = Category::with('products')->whereIn('id', $category??[])->get();
//
//
//        // home add 1
//        $ads1 = json_decode(get_setting('add1'));
//
//        $ads1?->items ? $ads1->items = Advised::whereIn('id', $ads1->items)->get() : [];
//
//        $ads2 = json_decode(get_setting('add2'));
//        $ads2?->items ? $ads2->items = Advised::whereIn('id', $ads2->items)->get() : [];
//
//        $topProducts = json_decode(get_setting('topProducts'));
//        $topProducts?->items ? $topProducts->items = Product::whereIn('id', $topProducts->items)->get() : [];
//
//        $topCategory = json_decode(get_setting('categories1'));
//        $topCategory?->items ? $topCategory->items = Category::whereIn('id', $topCategory->items)->with('products')->get() : [];
//
//        $homeCategories = json_decode(get_setting('categories2'));
//        $homeCategories?->items ? $homeCategories->items = Category::whereIn('id', $homeCategories->items)->with('products')->get() : [];
//
//        $ads4 = json_decode(get_setting('add4'));
//        $ads4?->items ? $ads4->items = Advised::whereIn('id', $ads4->items)->get() : [];
//
//        $add3 = json_decode(get_setting('add3'));
//        $add3?->items ? $add3->items = Advised::whereIn('id', $add3->items)->get() : [];
//
        $homeCats = Category::where('top', 1)->get();

        $promos = Promo::with('products')->get();

        return inertia("Frontend/Index",[
            'banners' => Banner::all(),
            'promos' => $promos,
            'pageData' =>[
                'homeCats' => $homeCats,
            ]

        ]);
    }


    public function showSubCategories($slug){
        return inertia('Frontend/Pages/ShowDetails',[
            'category' => Category::with('subCategories', 'products', 'products.category')->where('slug', $slug)->first(),
        ]);
    }
    public function showChildCategories($slug){
        return inertia('Frontend/Pages/ShowDetails',[
            'category' => SubCategory::with('category','childCategories', 'products', 'products.category')->where('slug', $slug)->first(),
        ]);
    }
    public function showChildCategoriesProducts($slug){
        return inertia('Frontend/Pages/ShowDetails',[
            'category' => ChildCategory::with('category','subCategory', 'products', 'products.category')->where('slug', $slug)->first(),
        ]);
    }


    public function fetchTopCategories(){
        return Category::with('subCategories', 'subCategories.childCategories')->whereNull('parent_id')->latest()->take(10)->get();
    }
    public function fetchFeaturedCategories(){
        return Category::with(['childrens', 'products', 'products.brand', 'products.category'])->whereNull('parent_id')->latest()->take(10)->get();

    }


    public function cartDetails(){
        return inertia('Frontend/Customer/CartDetails');
    }




//    public function index(){
//        ->when(Request::input('search'), function ($query, $search) {
//            $query->where('u_id', 'like', "%{$search}%")
//                ->orWhereHas('client', function ($client) use($search){
//                    $client
//                        ->where('name',    'like', "%{$search}%")
//                        ->orWhere('phone', 'like', "%{$search}%")
//                        ->orWhere('email', 'like', "%{$search}%")
//                    ;
//                })
//                ->orWhereHas('domains', function($hosting) use($search){
//                    $hosting->where('name', 'like', "%{$search}%");
//                })
//                ->orWhereHas('hostings', function($hosting) use($search){
//                    $hosting->where('name', 'like', "%{$search}%");
//                })
//                ->orWhereHas('works', function($hosting) use($search){
//                    $hosting->where('name', 'like', "%{$search}%");
//                })
//                ->orWhereHas('packages', function($hosting) use($search){
//                    $hosting->where('name', 'like', "%{$search}%");
//                })
//                ->orWhereHas('quotationItems', function($hosting) use($search){
//                    $hosting->where('item_name', 'like', "%{$search}%");
//                });
//        })
//            ->when(Request::input('byStatus'), function ($query, $search){
//                $query->where('status', $search);
//            })
//    }


    public function trams(){
            return inertia('Frontend/Trams');
    }

    public function policy(){
            return inertia('Frontend/Policy');
    }

    public function getSettings(){
        $key = Request::input('key');
        return get_setting($key);
    }

    public function getFooterSettings()
    {
//        $settings = json_decode(get_setting(Request::input('key')));
//        foreach ($settings as $key => $setting) {
//            $settings[$key]->pages = Page::with('seo')->whereIn('id', $setting->pages)->get();
//        }
//        return $settings;
        return Page::all();

    }


    public function getHeaderMenu()
    {
        return json_decode(get_setting(Request::input('key')));
    }


    public function pageView($slug){
        return inertia('Frontend/Page', [
            'info' => Page::with('seo')->where('slug', $slug)->first()
        ]);


    }

    public function showSinglePromo($id){
        return inertia("Frontend/Pages/SingleDells", [
            "promo" => Promo::with('products')->findOrFail($id),
        ]);
    }

}
