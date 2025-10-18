@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <form action="{{ route('checkout.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <h2 class="text-xl font-semibold">Shipping Address</h2>
        <div>
            <label class="block text-sm font-semibold">Line 1</label>
            <input type="text" name="address[line1]" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold">City</label>
                <input type="text" name="address[city]" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-semibold">Country</label>
                <input type="text" name="address[country]" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <h2 class="text-xl font-semibold pt-4">Payment</h2>
        <div>
            <label class="block text-sm font-semibold">Stripe Payment Method ID</label>
            <input type="text" name="payment[payment_method]" class="w-full border rounded px-3 py-2" placeholder="pm_xxx" required>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Place Order</button>
    </form>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
        <ul class="space-y-3">
            @foreach ($cart->items as $item)
                <li class="flex justify-between text-sm">
                    <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                    <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                </li>
            @endforeach
        </ul>
        <div class="mt-6 space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>${{ number_format($totals['subtotal'], 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tax</span>
                <span>${{ number_format($totals['tax'], 2) }}</span>
            </div>
            <div class="flex justify-between font-semibold text-lg">
                <span>Total</span>
                <span>${{ number_format($totals['grand_total'], 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
