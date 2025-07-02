<?php
namespace App\Repository;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products\Products;

class OrderRepository implements OrderRepositoryInterface
{
    public function placeOrder(array $data, array $cart)
    {
        $order = Order::create($data);
        foreach($cart as $item){
            $product = Products::find($item['id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_description' => $product->description,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'weight' => $product->weight,
                'total_price' => $item['price'] * $item['quantity'],
                'dimensions' => $product->dimensions,
                'image_url' => $product->urlImages,
            ]);
            $product->decrement('stock_quantity', $item['quantity']);
        }

        return $order;
    }
}