<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Queries\ProductSearchQuery;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(private ProductSearchQuery $search)
    {
    }

    public function index(Request $request)
    {
        $products = $this->search->apply($request->only('q', 'category', 'price_min', 'price_max'));
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return view('shop.index', compact('products', 'categories'));
    }
}
