<?php

namespace App\Http\Controllers;

use App\Actions\PlaceOrderAction;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cartService, private PlaceOrderAction $placeOrder)
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->forUserOrSession($request->user());
        $totals = $this->cartService->totals($cart);

        return view('checkout.index', compact('cart', 'totals'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = $this->cartService->forUserOrSession($request->user());
        $order = $this->placeOrder->execute($cart, $request->validated());

        return redirect()->route('orders.show', $order->number)->with('status', 'Order placed!');
    }
}
