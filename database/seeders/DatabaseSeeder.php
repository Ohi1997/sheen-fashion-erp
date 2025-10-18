<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Enums\UserRole;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => UserRole::Admin,
        ]);

        $categories = Category::factory(10)->create();
        $categories->each(function (Category $category) use ($categories) {
            if (rand(0, 1)) {
                $category->update(['parent_id' => $categories->random()->id]);
            }
        });

        Product::factory(40)->create()->each(function (Product $product) use ($categories) {
            $product->categories()->attach($categories->random(rand(1, 3))->pluck('id')->toArray());
            $product->addMediaFromUrl('https://via.placeholder.com/800x600.png?text=' . urlencode($product->name))
                ->toMediaCollection('images');
        });

        Banner::factory()->count(3)->create()->each(function (Banner $banner) {
            $banner->addMediaFromUrl('https://via.placeholder.com/1600x400.png?text=' . urlencode($banner->title))
                ->toMediaCollection('images');
        });

        Post::factory()->count(3)->create([
            'user_id' => $admin->id,
        ]);
    }
}
