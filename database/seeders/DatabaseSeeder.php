<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// IMPORTANT: import all seeders you are calling
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SettingsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
