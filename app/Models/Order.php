<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'number','status','customer_name','customer_phone','customer_email',
        'shipping_address','total'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->number)) {
                $order->number = 'ORD-' . now()->format('YmdHis') . '-' . str()->upper(str()->random(4));
            }
            if ($order->total === null) {
                $order->total = 0;
            }
        });
    }

    // Вызывается из событий OrderItem
    public function recalcTotal(): void
    {
        $sum = (float) $this->items()->sum('sum');
        if ((float) $this->total !== $sum) {
            $this->forceFill(['total' => $sum])->saveQuietly();
        }
    }
}
