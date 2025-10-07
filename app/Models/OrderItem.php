<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id','product_id','name','sku','qty','price','sum'];

    protected static function booted()
    {
        static::saving(function ($item) {
            $item->sum = $item->qty * $item->price;
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
