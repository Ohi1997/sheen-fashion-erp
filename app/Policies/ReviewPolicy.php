<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user, Product $product): bool
    {
        return $user->orders()
            ->where('status', 'paid')
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->exists();
    }
}
