@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="bg-white rounded shadow p-6">
    <h1 class="text-2xl font-semibold mb-4">Your Cart</h1>
    <div class="space-y-4">
        @forelse ($cart->items as $item)
            <div class="flex items-center justify-between border-b pb-4">
                <div>
                    <h2 class="font-semibold">{{ $item->product->name }}</h2>
                    <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} x {{ $item->quantity }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border rounded px-2 py-1">
                        <button type="submit" class="text-sm text-indigo-600">Update</button>
                    </form>
                    <form action="{{ route('cart.destroy', $item) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600">Remove</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">Your cart is empty.</p>
        @endforelse
    </div>
    <div class="mt-6 flex justify-end space-x-6">
        <div>
            <p class="text-sm text-gray-500">Subtotal</p>
            <p class="text-lg font-semibold">${{ number_format($totals['subtotal'], 2) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Tax</p>
            <p class="text-lg font-semibold">${{ number_format($totals['tax'], 2) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-lg font-semibold">${{ number_format($totals['grand_total'], 2) }}</p>
        </div>
    </div>
    <div class="mt-6 text-right">
        <a href="{{ route('checkout.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Proceed to Checkout</a>
    </div>
</div>
@endsection
