<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function show(string $number)
    {
        $order = Order::with('items', 'payments')->where('number', $number)->firstOrFail();
        $this->authorize('view', $order);

        return view('orders.show', compact('order'));
    }
}
