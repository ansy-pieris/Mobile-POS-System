<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Main categories
        $phone = Category::create(['name' => 'Phone', 'type' => 'product']);
        $accessory = Category::create(['name' => 'Accessory', 'type' => 'product']);
        Category::create(['name' => 'Repair', 'type' => 'service']);

        // Phone brands
        Category::create(['name' => 'Samsung', 'type' => 'product', 'parent_id' => $phone->id]);
        Category::create(['name' => 'Nokia', 'type' => 'product', 'parent_id' => $phone->id]);

        // Accessories
        Category::create(['name' => 'Headset', 'type' => 'product', 'parent_id' => $accessory->id]);
        Category::create(['name' => 'Charger', 'type' => 'product', 'parent_id' => $accessory->id]);
        Category::create(['name' => 'Case', 'type' => 'product', 'parent_id' => $accessory->id]);
    }
}
