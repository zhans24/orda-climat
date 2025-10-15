<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'number',
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',

        'face',
        'delivery_method',

        'shipping_address',
        'total',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'total'            => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->number)) {
                $order->number = 'ORD-'.now()->format('YmdHis').'-'.strtoupper(str()->random(4));
            }
            $order->status ??= 'new';
            $order->total  ??= 0;
        });
    }


    public function recalcTotal(): void
    {
        $itemsSum = (float) ($this->items()
            ->selectRaw('COALESCE(SUM(quantity * price), 0) as s')
            ->value('s') ?? 0);

        $delivery = (float) data_get($this->shipping_address, 'delivery_price', 0);

        $newTotal = $itemsSum + $delivery;

        if ((float)$this->total !== $newTotal) {
            $this->forceFill(['total' => $newTotal])->saveQuietly();
        }
    }
}
