@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="space-y-4">
        <h1 class="text-2xl font-semibold">Contact Us</h1>
        <p>We would love to hear from you. Fill out the form and our team will get back to you within 24 hours.</p>
        <p class="text-sm text-gray-600">Email: support@example.com<br>Phone: +1 (800) 555-1234</p>
    </div>
    <form action="{{ route('contact.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-semibold">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-semibold">Message</label>
            <textarea name="message" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Send Message</button>
    </form>
</div>
@endsection
