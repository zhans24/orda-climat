<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id','product_id','name','code','quantity','price','sum'];

    protected static function booted(): void
    {
        static::saving(function (OrderItem $item) {
            $item->sum = (float) $item->quantity * (float) $item->price;
        });

        static::saved(function (OrderItem $item) {
            $item->order?->recalcTotal();
        });

        static::deleted(function (OrderItem $item) {
            $item->order?->recalcTotal();
        });
    }

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
