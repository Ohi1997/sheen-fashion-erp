<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    public function forUserOrSession(?User $user = null): Cart
    {
        $sessionId = session()->get('cart_session_id');
        if (! $sessionId) {
            $sessionId = (string) Str::uuid();
            session()->put('cart_session_id', $sessionId);
        }

        $cart = Cart::query()
            ->with('items.product')
            ->when($user, fn ($query) => $query->where('user_id', $user->id))
            ->when(! $user, fn ($query) => $query->where('session_id', $sessionId))
            ->first();

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => $user?->id,
                'session_id' => $user ? null : $sessionId,
                'currency' => 'USD',
            ]);
        }

        if ($user && $cart->user_id === null) {
            $cart->update([
                'user_id' => $user->id,
                'session_id' => null,
            ]);
        }

        return $cart->load('items.product');
    }

    public function addProduct(Cart $cart, Product $product, int $quantity = 1): CartItem
    {
        $item = $cart->items()->firstOrCreate(
            ['product_id' => $product->id],
            ['price' => $product->price]
        );

        $item->increment('quantity', $quantity);

        return $item->refresh();
    }

    public function updateItem(CartItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            $item->delete();
            return;
        }

        $item->update(['quantity' => $quantity]);
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    public function merge(?Cart $guestCart, Cart $userCart): void
    {
        if (! $guestCart || $guestCart->id === $userCart->id) {
            return;
        }

        foreach ($guestCart->items as $guestItem) {
            $item = $userCart->items()->firstOrCreate(
                ['product_id' => $guestItem->product_id],
                ['price' => $guestItem->price]
            );
            $item->increment('quantity', $guestItem->quantity);
        }

        $guestCart->items()->delete();
        $guestCart->delete();
    }

    public function totals(Cart $cart): array
    {
        $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->quantity);
        $tax = round($subtotal * 0.1, 2);
        $grand = $subtotal + $tax;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'grand_total' => $grand,
        ];
    }
}
