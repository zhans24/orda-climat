<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data  = $request->validated();

        $items = $data['items'] ?? [];
        if (empty($items)) {
            return back()->withErrors(['items' => 'Корзина пуста'])->withInput();
        }

        $deliveryMethod = ((int)($data['delivery'] ?? 0) === 1) ? 'delivery' : 'pickup';
        $deliveryPrice  = (float)($data['delivery_price'] ?? 0);

        $shippingAddress = [
            'city'           => (string)($data['city'] ?? ''),
            'address'        => (string)($data['address'] ?? ''),
            'message'        => (string)($data['message'] ?? ''),
            'delivery_price' => $deliveryPrice,
        ];

        $order = DB::transaction(function () use ($data, $items, $shippingAddress, $deliveryMethod) {

            $order = Order::create([
                'number'          => null,
                'status'          => 'new',
                'customer_name'   => (string)($data['customer_name'] ?? ''),
                'customer_phone'  => (string)($data['customer_phone'] ?? ''),
                'customer_email'  => (string)($data['customer_email'] ?? ''),

                'face'            => (string)($data['face'] ?? 'person'),
                'delivery_method' => $deliveryMethod,

                'shipping_address'=> $shippingAddress,
                'total'           => 0,
            ]);

            foreach ($items as $row) {
                $productId = $row['product_id'] ?? null;
                $name      = (string)($row['name'] ?? '');
                $code      = $row['code'] ?? null;
                $price     = (float)($row['price'] ?? 0);
                $quantity  = (int)($row['quantity'] ?? 1);

                if ($productId && ($product = Product::find($productId))) {
                    $name  = $name ?: $product->name;
                    $price = $price > 0 ? $price : (float)$product->price;
                }

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'name'       => $name,
                    'code'       => $code,
                    'quantity'   => max(1, $quantity),
                    'price'      => $price,
                ]);
            }

            $order->recalcTotal();

            return $order;
        });

        return redirect()->route('home')
            ->with('success', 'Заказ оформлен: '.$order->number);
    }
}
