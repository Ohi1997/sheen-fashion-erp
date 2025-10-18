@extends('layouts.app')

@section('title', 'Order ' . $order->number)

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-semibold mb-2">Thank you!</h1>
    <p class="text-sm text-gray-600 mb-6">Your order number is <span class="font-semibold">{{ $order->number }}</span>.</p>
    <h2 class="text-xl font-semibold mb-3">Items</h2>
    <ul class="space-y-2">
        @foreach ($order->items as $item)
            <li class="flex justify-between text-sm">
                <span>{{ $item->name_snapshot }} x {{ $item->quantity }}</span>
                <span>${{ number_format($item->total, 2) }}</span>
            </li>
        @endforeach
    </ul>
    <div class="mt-6 text-right text-lg font-semibold">
        Total: ${{ number_format($order->grand_total, 2) }}
    </div>
</div>
@endsection
