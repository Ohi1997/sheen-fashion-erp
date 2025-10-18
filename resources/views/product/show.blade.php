@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div>
        <img src="{{ optional($product->getFirstMedia('images'))->getUrl() ?? 'https://via.placeholder.com/600' }}" class="w-full rounded shadow" alt="{{ $product->name }}">
        <div class="mt-4 flex space-x-2">
            @foreach ($product->getMedia('images') as $media)
                <img src="{{ $media->getUrl('thumb') ?? $media->getUrl() }}" class="h-20 w-20 object-cover rounded" alt="{{ $product->name }}">
            @endforeach
        </div>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h1 class="text-3xl font-semibold mb-2">{{ $product->name }}</h1>
        <p class="text-gray-500 mb-4">${{ number_format($product->price, 2) }}</p>
        <p class="mb-6">{{ $product->description }}</p>
        <form action="{{ route('cart.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="product_slug" value="{{ $product->slug }}">
            <label class="block">
                <span class="text-sm font-semibold">Quantity</span>
                <input type="number" name="quantity" value="1" min="1" class="border rounded px-3 py-2 w-24">
            </label>
            <div class="flex space-x-3">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Add to Cart</button>
                <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded">Toggle Wishlist</button>
                </form>
            </div>
        </form>
        <section class="mt-8">
            <h2 class="text-xl font-semibold mb-3">Reviews</h2>
            @forelse ($product->reviews as $review)
                <div class="border-b py-3">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">{{ $review->user->name }}</span>
                        <span class="text-sm text-yellow-500">{{ str_repeat('★', $review->rating) }}</span>
                    </div>
                    <p class="text-sm text-gray-600">{{ $review->body }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500">No reviews yet.</p>
            @endforelse
            @auth
                <form action="{{ route('reviews.store') }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <label class="block text-sm font-semibold">Rating</label>
                        <select name="rating" class="border rounded px-3 py-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold">Review</label>
                        <textarea name="body" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Submit Review</button>
                </form>
            @else
                <p class="text-sm text-gray-500 mt-4">Please log in to leave a review.</p>
            @endauth
        </section>
    </div>
</div>
@endsection
