@extends('layouts.app')

@section('title', 'Create Banner')

@section('content')
<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-semibold">Title</label>
        <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-semibold">Link URL</label>
        <input type="url" name="link_url" class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-semibold">Sort Order</label>
        <input type="number" name="sort_order" class="w-full border rounded px-3 py-2" value="0">
    </div>
    <div>
        <label class="block text-sm font-semibold">Image</label>
        <input type="file" name="image" required>
    </div>
    <label class="inline-flex items-center">
        <input type="checkbox" name="is_active" value="1" checked>
        <span class="ml-2">Active</span>
    </label>
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Save</button>
</form>
@endsection
