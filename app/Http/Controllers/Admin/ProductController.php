<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $products = Product::with('categories')->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'unique:products,sku'],
            'name' => ['required', 'string'],
            'slug' => ['required', 'string', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'inventory' => ['required', 'integer'],
            'is_active' => ['boolean'],
            'featured' => ['boolean'],
            'categories' => ['array'],
        ]);

        $product = Product::create($data);
        $product->categories()->sync($data['categories'] ?? []);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'unique:products,sku,' . $product->id],
            'name' => ['required', 'string'],
            'slug' => ['required', 'string', 'unique:products,slug,' . $product->id],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'inventory' => ['required', 'integer'],
            'is_active' => ['boolean'],
            'featured' => ['boolean'],
            'categories' => ['array'],
        ]);

        $product->update($data);
        $product->categories()->sync($data['categories'] ?? []);

        if ($request->hasFile('images')) {
            $product->clearMediaCollection('images');
            foreach ($request->file('images') as $image) {
                $product->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }
}
