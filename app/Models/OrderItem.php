<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // если колонка количества называется quantity:
    protected $fillable = ['order_id','product_id','name','code','quantity','price','sum','montage'];

    // если у тебя НЕ quantity, а qty — замени выше и в месте ниже!

    protected static function booted(): void
    {
        static::saving(function (OrderItem $item) {
            // для quantity:
            $qty = (float) $item->quantity;
            // если у тебя колонка qty, то:
            // $qty = (float) $item->qty;

            $item->sum = $qty * (float) $item->price;
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
