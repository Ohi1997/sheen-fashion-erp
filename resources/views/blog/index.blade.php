@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Latest Posts</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($posts as $post)
            <article class="bg-white rounded shadow p-5">
                <h2 class="text-xl font-semibold mb-2">
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-indigo-600">{{ $post->title }}</a>
                </h2>
                <p class="text-sm text-gray-500 mb-3">{{ optional($post->published_at)->format('M d, Y') }}</p>
                <p class="text-sm text-gray-600">{{ Str::limit($post->excerpt, 120) }}</p>
            </article>
        @endforeach
    </div>
    {{ $posts->links() }}
</div>
@endsection
