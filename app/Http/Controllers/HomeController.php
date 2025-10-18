<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::query()->where('is_active', true)->orderBy('sort_order')->get();
        $products = Product::query()->active()->where('featured', true)->take(8)->get();

        return view('home.index', compact('banners', 'products'));
    }
}
