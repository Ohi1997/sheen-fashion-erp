<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->forUserOrSession($request->user());
        $totals = $this->cartService->totals($cart);

        return view('cart.index', compact('cart', 'totals'));
    }

    public function store(CartItemRequest $request)
    {
        $cart = $this->cartService->forUserOrSession($request->user());
        $product = Product::where('slug', $request->get('product_slug'))->firstOrFail();
        $this->cartService->addProduct($cart, $product, $request->integer('quantity'));

        return redirect()->route('cart.index')->with('status', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $item)
    {
        $this->authorize('update', $item->cart);
        $this->cartService->updateItem($item, (int) $request->input('quantity', 1));

        return back()->with('status', 'Cart updated.');
    }

    public function destroy(CartItem $item)
    {
        $this->authorize('update', $item->cart);
        $this->cartService->removeItem($item);

        return back()->with('status', 'Item removed.');
    }
}
