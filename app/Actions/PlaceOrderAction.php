<?php

namespace App\Actions;

use App\Events\OrderPlaced;
use App\Mail\OrderPlacedMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\InventoryService;
use App\Services\Payments\PaymentGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PlaceOrderAction
{
    public function __construct(
        private CartService $cartService,
        private InventoryService $inventory,
        private PaymentGateway $gateway
    ) {
    }

    public function execute(Cart $cart, array $data): Order
    {
        return DB::transaction(function () use ($cart, $data) {
            $totals = $this->cartService->totals($cart);

            $order = Order::create([
                'user_id' => $cart->user_id,
                'number' => strtoupper(Str::random(10)),
                'status' => 'pending',
                'subtotal' => $totals['subtotal'],
                'discount_total' => 0,
                'tax_total' => $totals['tax'],
                'shipping_total' => 0,
                'grand_total' => $totals['grand_total'],
                'currency' => 'USD',
                'payment_method' => 'card',
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $this->inventory->reserve($item->product, $item->quantity);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'name_snapshot' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->price * $item->quantity,
                ]);
            }

            $chargeId = $this->gateway->charge($order->refresh()->load('items'), $data['payment'] ?? []);

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'stripe',
                'amount' => $order->grand_total,
                'currency' => 'USD',
                'status' => 'succeeded',
                'provider_charge_id' => $chargeId,
                'payload' => $data['payment'] ?? [],
            ]);

            $order->update(['status' => 'paid']);

            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
            event(new OrderPlaced($order));

            $cart->items()->delete();

            return $order->fresh('items', 'payments');
        });
    }
}
