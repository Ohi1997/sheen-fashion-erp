@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="space-y-10">
    <section x-data="{ current: 0 }" class="relative">
        <div class="overflow-hidden rounded-lg shadow">
            <template x-for="(banner, index) in {{ $banners->toJson() }}" :key="banner.id">
                <div x-show="current === index" class="h-72 bg-cover bg-center flex items-center justify-center text-white text-2xl font-semibold" :style="`background-image:url('${banner.media?.[0]?.original_url ?? ''}')`">
                    <a :href="banner.link_url" class="bg-black bg-opacity-50 px-6 py-3 rounded">{{ '{{ banner.title }}' }}</a>
                </div>
            </template>
        </div>
        <div class="absolute inset-y-0 left-0 flex items-center">
            <button @click="current = (current - 1 + {{ $banners->count() }}) % {{ $banners->count() }}" class="bg-white/70 p-2 rounded-full">‹</button>
        </div>
        <div class="absolute inset-y-0 right-0 flex items-center">
            <button @click="current = (current + 1) % {{ $banners->count() }}" class="bg-white/70 p-2 rounded-full">›</button>
        </div>
    </section>
    <section>
        <h2 class="text-2xl font-semibold mb-4">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded shadow p-4 flex flex-col">
                    <img src="{{ optional($product->getFirstMedia('images'))->getUrl() ?? 'https://via.placeholder.com/300' }}" class="h-40 object-cover rounded mb-4" alt="{{ $product->name }}">
                    <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 flex-1">{{ Str::limit($product->description, 80) }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="font-semibold">${{ number_format($product->price, 2) }}</span>
                        <a href="{{ route('products.show', $product->slug) }}" class="text-indigo-600 text-sm">View</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
