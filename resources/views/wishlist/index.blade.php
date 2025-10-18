@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Your Wishlist</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($wishlist->products as $product)
            <div class="border rounded p-4 flex flex-col">
                <img src="{{ optional($product->getFirstMedia('images'))->getUrl() ?? 'https://via.placeholder.com/300' }}" class="h-36 object-cover rounded mb-3" alt="{{ $product->name }}">
                <h3 class="font-semibold">{{ $product->name }}</h3>
                <span class="text-sm text-gray-500">${{ number_format($product->price, 2) }}</span>
                <a href="{{ route('products.show', $product->slug) }}" class="mt-auto text-indigo-600 text-sm">View Product</a>
            </div>
        @empty
            <p class="text-sm text-gray-500">No items in wishlist.</p>
        @endforelse
    </div>
</div>
@endsection
