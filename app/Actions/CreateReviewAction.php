<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;

class CreateReviewAction
{
    public function execute(User $user, array $data): Review
    {
        $order = Order::query()
            ->where('status', 'paid')
            ->where('user_id', $user->id)
            ->whereHas('items', function ($query) use ($data) {
                $query->where('product_id', $data['product_id']);
            })
            ->firstOrFail();

        return Review::create([
            'user_id' => $user->id,
            'product_id' => $data['product_id'],
            'order_id' => $order->id,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'] ?? null,
            'is_approved' => false,
        ]);
    }
}
