<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['media', 'reviews.user'])->where('slug', $slug)->firstOrFail();

        $related = Product::query()
            ->whereHas('categories', function ($q) use ($product) {
                return $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('product.show', compact('product', 'related'));
    }
}
