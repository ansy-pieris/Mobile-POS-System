<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Settings::create([
            'shop_name' => 'Mobile City',
            'address' => '123 Main Street',
            'phone' => '0771234567',
            'email' => 'mobilecity@gmail.com',
        ]);
    }
}
