<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function reserve(Product $product, int $quantity): void
    {
        DB::transaction(function () use ($product, $quantity) {
            $fresh = Product::query()->lockForUpdate()->find($product->id);
            if ($fresh->inventory < $quantity) {
                throw new \RuntimeException('Insufficient inventory.');
            }

            $fresh->decrement('inventory', $quantity);
        });
    }
}
