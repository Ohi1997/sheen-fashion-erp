@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Admin Dashboard</h1>
    <p>Quick links:</p>
    <ul class="list-disc pl-6 text-sm text-indigo-600 space-y-1">
        <li><a href="{{ route('admin.products.index') }}">Manage Products</a></li>
        <li><a href="{{ route('admin.categories.index') }}">Manage Categories</a></li>
        <li><a href="{{ route('admin.banners.index') }}">Manage Banners</a></li>
        <li><a href="{{ route('admin.posts.index') }}">Manage Posts</a></li>
        <li><a href="{{ route('admin.orders.index') }}">Manage Orders</a></li>
    </ul>
</div>
@endsection
