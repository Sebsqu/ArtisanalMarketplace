<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\Products\Products;
use App\Models\User;
use App\Models\Order;

class OrderNotificationService
{
    public function notifyOwners(Order $order)
    {
        foreach ($order->orderItems as $item) {
            $product = Products::find($item->product_id);
            if (!$product) continue;
            
            $owner = User::find($product->user_id);
            if (!$owner || !$owner->email) continue;
            
            Mail::raw(
                "Twój produkt został sprzedany w zamówieniu nr {$order->id}:\n" .
                "- {$item->product_name} (ilość: {$item->quantity})",
                function($message) use ($owner, $order) {
                    $message->to($owner->email)
                        ->subject("Sprzedaż Twojego produktu - zamówienie nr {$order->id}");
                }
            );
        }
    }

    public function notifyCustomer(Order $order)
    {
        Mail::raw(
            "Dziękujemy za złożenie zamówienia nr {$order->id}!\n\n" .
            "Podsumowanie zamówienia:\n" .
            $order->orderItems->map(function($item) {
                return "- {$item->product_name} (ilość: {$item->quantity}, cena: {$item->price} zł)";
            })->implode("\n") .
            "\n\nŁączna kwota: {$order->total_price} zł\n",
            function($message) use ($order) {
                $message->to($order->email)
                    ->subject("Potwierdzenie zamówienia nr {$order->id}");
            }
        );
    }
}
