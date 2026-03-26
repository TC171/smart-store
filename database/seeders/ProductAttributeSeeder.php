<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            'Đen', 'Trắng', 'Xanh dương', 'Đỏ', 'Xanh lá', 'Vàng', 'Tím', 'Hồng',
        ];

        $storages = [
            '64GB', '128GB', '256GB', '512GB', '1TB', '2TB',
        ];

        $rams = [
            '4GB', '6GB', '8GB', '12GB', '16GB', '32GB',
        ];

        foreach ($colors as $index => $color) {
            \App\Models\ProductAttribute::create([
                'type' => 'color',
                'value' => $color,
                'sort_order' => $index + 1,
            ]);
        }

        foreach ($storages as $index => $storage) {
            \App\Models\ProductAttribute::create([
                'type' => 'storage',
                'value' => $storage,
                'sort_order' => $index + 1,
            ]);
        }

        foreach ($rams as $index => $ram) {
            \App\Models\ProductAttribute::create([
                'type' => 'ram',
                'value' => $ram,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
