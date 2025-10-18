<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Shop')</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow">
            <div class="container mx-auto px-4 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-xl font-semibold">{{ config('app.name') }}</a>
                <nav class="space-x-4">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
                    <a href="{{ route('shop.index') }}" class="hover:text-indigo-600">Shop</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-indigo-600">Blog</a>
                    <a href="{{ route('communities.index') }}" class="hover:text-indigo-600">Communities</a>
                    <a href="{{ route('contact.index') }}" class="hover:text-indigo-600">Contact Us</a>
                </nav>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('wishlist.index') }}" class="text-sm">Wishlist</a>
                    <a href="{{ route('cart.index') }}" class="text-sm">Cart</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-500">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm">Login</a>
                        <a href="{{ route('register') }}" class="text-sm">Register</a>
                    @endauth
                </div>
            </div>
        </header>
        <main class="flex-1 container mx-auto px-4 py-8">
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif
            @yield('content')
        </main>
        <footer class="bg-white border-t">
            <div class="container mx-auto px-4 py-6 text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>
