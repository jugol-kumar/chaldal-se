<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\OrderRefand;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class RefundController extends Controller
{
    public function index(){

        return inertia('Refund/Index', [
            'refunds' => OrderRefand::query()
                ->with("order")
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('region', 'like', "%{$search}%")
                        ->orWhere('about_problem',  'like', "%{$search}%")
                        ->orWhere('status',  'like', "%{$search}%");
                })
                ->latest()
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($refund) => [
                    'id' => $refund->id,
                    'order' => $refund->order,
                    'title' => $refund->region,
                    "prob_img" => $refund->problem_image,
                    "about" => $refund->about_problem,
                    "status" => $refund->status,
                    'created_at' => $refund->created_at->format(config('app.date_format')),
                    'updateIsTop' => URL::route('admin.makeFeatured', $refund->id)
                ]),
            'filters' => Request::only(['search','perPage', 'dateRange']),
            'main_url' => URL::route('admin.refund.index')
        ]);
    }

    public function show($id){
        return OrderRefand::with('order')->findOrFail($id);
    }


    public function chandeRefandStatus($id){
        $order = OrderRefand::findOrFail($id);
        $order->update(['status' => Request::input('status')]);
        return Redirect::route('admin.refund.index');
    }






}
