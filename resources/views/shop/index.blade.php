@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <aside class="bg-white p-4 rounded shadow">
        <form method="GET" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" class="w-full border rounded px-3 py-2" placeholder="Search products...">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Price Range</label>
                <div class="flex space-x-2">
                    <input type="number" name="price_min" value="{{ request('price_min') }}" class="w-1/2 border rounded px-3 py-2" placeholder="Min">
                    <input type="number" name="price_max" value="{{ request('price_max') }}" class="w-1/2 border rounded px-3 py-2" placeholder="Max">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Categories</label>
                <ul class="space-y-1">
                    @foreach ($categories as $category)
                        <li>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="category" value="{{ $category->slug }}" @checked(request('category') === $category->slug)>
                                <span>{{ $category->name }}</span>
                            </label>
                            @if ($category->children->isNotEmpty())
                                <ul class="pl-5 text-sm text-gray-600 space-y-1">
                                    @foreach ($category->children as $child)
                                        <li>
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="category" value="{{ $child->slug }}" @checked(request('category') === $child->slug)>
                                                <span>{{ $child->name }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded">Apply Filters</button>
        </form>
    </aside>
    <section class="lg:col-span-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded shadow p-4 flex flex-col">
                    <img src="{{ optional($product->getFirstMedia('images'))->getUrl() ?? 'https://via.placeholder.com/300' }}" class="h-40 object-cover rounded mb-3" alt="{{ $product->name }}">
                    <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 flex-1">{{ Str::limit($product->description, 90) }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="font-semibold">${{ number_format($product->price, 2) }}</span>
                        <a href="{{ route('products.show', $product->slug) }}" class="text-indigo-600 text-sm">View</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </section>
</div>
@endsection
