@extends('layouts.app')

@section('title', 'Manage Banners')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-semibold">Banners</h1>
    <a href="{{ route('admin.banners.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Create</a>
</div>
<div class="bg-white rounded shadow">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-100 text-left text-sm">
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banners as $banner)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $banner->title }}</td>
                    <td class="px-4 py-2">{{ $banner->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="text-indigo-600 text-sm">Edit</a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $banners->links() }}</div>
@endsection
