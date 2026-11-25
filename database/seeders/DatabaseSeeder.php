<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::create([
            'name' => ['fa' => 'کالای دیجیتال', 'en' => 'Digital Goods'],
            'slug' => 'digital-goods', 'is_active' => true, 'sort_order' => 1,
        ]);

        Product::create([
            'category_id' => $category->id, 'sku' => 'DEMO-001', 'slug' => 'sample-product',
            'name' => ['fa' => 'محصول نمونه فروشگاه', 'en' => 'Sample Store Product'],
            'description' => ['fa' => 'این محصول صرفاً برای آزمایش نصب پروژه است.', 'en' => 'Demo product for installation testing.'],
            'price' => 12500000, 'compare_at_price' => 14900000, 'stock' => 15,
            'status' => 'active', 'published_at' => now(),
        ]);
    }
}
