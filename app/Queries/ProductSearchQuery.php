<?php

namespace App\Queries;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductSearchQuery
{
    public function __construct(private Product $product)
    {
    }

    public function apply(array $filters = []): LengthAwarePaginator
    {
        $query = $this->product->newQuery()->with('media', 'categories')->active();

        if ($search = $filters['q'] ?? null) {
            $query->where(function (Builder $builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $filters['category'] ?? null) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $category));
        }

        if ($min = $filters['price_min'] ?? null) {
            $query->where('price', '>=', $min);
        }

        if ($max = $filters['price_max'] ?? null) {
            $query->where('price', '<=', $max);
        }

        return $query->orderByDesc('featured')->orderBy('name')->paginate(12)->withQueryString();
    }
}
