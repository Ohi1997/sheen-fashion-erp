<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        $wishlist = $request->user()->wishlist()->with('products.media')->firstOrCreate();

        return view('wishlist.index', ['wishlist' => $wishlist]);
    }

    public function toggle(Request $request, Product $product): RedirectResponse
    {
        $wishlist = $request->user()->wishlist()->firstOrCreate();
        $exists = $wishlist->products()->where('product_id', $product->id)->exists();

        if ($exists) {
            $wishlist->products()->detach($product->id);
        } else {
            $wishlist->products()->attach($product->id);
        }

        return back()->with('status', 'Wishlist updated.');
    }
}
