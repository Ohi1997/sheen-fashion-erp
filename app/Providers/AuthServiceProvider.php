<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Policies\OrderPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Order::class => OrderPolicy::class,
    ];

    public function boot(): void
    {
        Gate::policy(Product::class, ReviewPolicy::class);
    }
}
