@extends('layouts.app')

@section('title', $post->title)

@section('content')
<article class="bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-semibold mb-3">{{ $post->title }}</h1>
    <p class="text-sm text-gray-500 mb-4">{{ optional($post->published_at)->format('M d, Y') }}</p>
    <div class="prose max-w-none">
        {!! nl2br(e($post->body)) !!}
    </div>
</article>
@endsection
