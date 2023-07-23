<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Promo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function banner(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : asset('img/logo.png'),
        );
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'promo_products')->withPivot('discount', 'discount_type');
    }

}
