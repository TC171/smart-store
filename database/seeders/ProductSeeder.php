<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Lấy Category và Brand đầu tiên, nếu không có thì tạo mới với id = 1
        $category = Category::firstOrCreate(
            ['name' => 'Default Category'], // Đây là tên category mặc định, có thể thay đổi
            ['slug' => 'default-category']  // Thêm slug nếu cần
        );

        $brand = Brand::firstOrCreate(
            ['name' => 'Default Brand'], // Tên brand mặc định
            ['slug' => 'default-brand']  // Thêm slug nếu cần
        );

        // Tạo 5 sản phẩm cùng các biến thể
        for ($i = 1; $i <= 5; $i++) {
            // Tạo sản phẩm
            $product = Product::create([
                'name' => 'iPhone Test ' . $i,
                'slug' => 'iphone-test-' . $i,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'status' => 1,
            ]);

            // Tạo biến thể cho sản phẩm
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => 'IPT' . $i,
                'price' => 20000000,
                'sale_price' => 18000000,
                'stock' => 50,
            ]);
        }
    }
}