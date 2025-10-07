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

    protected static function booted()
    {
        static::creating(function ($order) {
            $lastId = (static::max('id') ?? 0) + 1;
            $order->number = 'ORD-'.now()->format('Y').'-'.str_pad($lastId, 5, '0', STR_PAD_LEFT);
        });
    }
}
