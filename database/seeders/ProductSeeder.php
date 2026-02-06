<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $samsung = Category::where('name','Samsung')->first();
        $nokia = Category::where('name','Nokia')->first();
        $headset = Category::where('name','Headset')->first();
        $charger = Category::where('name','Charger')->first();
        $case = Category::where('name','Case')->first();

        Product::insert([
            [
                'product_code' => 'SMG_S23',
                'name' => 'Samsung Galaxy S23',
                'category_id' => $samsung->id,
                'price' => 1200,
                'stock_quantity' => 10,
                'warranty_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_code' => 'NOK_3310',
                'name' => 'Nokia 3310',
                'category_id' => $nokia->id,
                'price' => 80,
                'stock_quantity' => 15,
                'warranty_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_code' => 'ACC_HEAD01',
                'name' => 'Headset ABC',
                'category_id' => $headset->id,
                'price' => 25,
                'stock_quantity' => 50,
                'warranty_available' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_code' => 'ACC_CHG01',
                'name' => 'Charger XYZ',
                'category_id' => $charger->id,
                'price' => 15,
                'stock_quantity' => 100,
                'warranty_available' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_code' => 'ACC_CASE01',
                'name' => 'Case DEF',
                'category_id' => $case->id,
                'price' => 10,
                'stock_quantity' => 60,
                'warranty_available' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
