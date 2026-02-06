<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Dashboard Test Seeder
 * 
 * Seeds the database with realistic sample data for testing
 * the KPI dashboard. Creates invoices spanning multiple months
 * with varied categories and amounts.
 * 
 * Usage: php artisan db:seed --class=DashboardTestSeeder
 */
class DashboardTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test user if not exists
        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        // Create parent categories (phones, accessories, services)
        $phoneCategory = Category::firstOrCreate(
            ['name' => 'Phones', 'type' => 'product'],
            ['parent_id' => null]
        );
        
        $accessoryCategory = Category::firstOrCreate(
            ['name' => 'Accessories', 'type' => 'product'],
            ['parent_id' => null]
        );
        
        $serviceCategory = Category::firstOrCreate(
            ['name' => 'Services', 'type' => 'service'],
            ['parent_id' => null]
        );

        // Create sample products for each category
        $products = [
            // Phones
            [
                'product_code' => 'PHONE-001',
                'name' => 'Samsung Galaxy S24',
                'category_id' => $phoneCategory->id,
                'price' => 125000.00,
                'cost_price' => 95000.00,
                'stock_quantity' => 50,
                'sale_category' => 'phones',
            ],
            [
                'product_code' => 'PHONE-002',
                'name' => 'iPhone 15 Pro',
                'category_id' => $phoneCategory->id,
                'price' => 185000.00,
                'cost_price' => 145000.00,
                'stock_quantity' => 30,
                'sale_category' => 'phones',
            ],
            [
                'product_code' => 'PHONE-003',
                'name' => 'Xiaomi 14',
                'category_id' => $phoneCategory->id,
                'price' => 65000.00,
                'cost_price' => 48000.00,
                'stock_quantity' => 80,
                'sale_category' => 'phones',
            ],
            // Accessories
            [
                'product_code' => 'ACC-001',
                'name' => 'Wireless Earbuds',
                'category_id' => $accessoryCategory->id,
                'price' => 8500.00,
                'cost_price' => 4500.00,
                'stock_quantity' => 200,
                'sale_category' => 'accessories',
            ],
            [
                'product_code' => 'ACC-002',
                'name' => 'Phone Case Premium',
                'category_id' => $accessoryCategory->id,
                'price' => 2500.00,
                'cost_price' => 800.00,
                'stock_quantity' => 500,
                'sale_category' => 'accessories',
            ],
            [
                'product_code' => 'ACC-003',
                'name' => 'Fast Charger 65W',
                'category_id' => $accessoryCategory->id,
                'price' => 4500.00,
                'cost_price' => 2200.00,
                'stock_quantity' => 150,
                'sale_category' => 'accessories',
            ],
            // Services
            [
                'product_code' => 'SVC-001',
                'name' => 'Screen Replacement',
                'category_id' => $serviceCategory->id,
                'price' => 15000.00,
                'cost_price' => 8000.00,
                'stock_quantity' => null,
                'sale_category' => 'services',
            ],
            [
                'product_code' => 'SVC-002',
                'name' => 'Battery Replacement',
                'category_id' => $serviceCategory->id,
                'price' => 5500.00,
                'cost_price' => 2500.00,
                'stock_quantity' => null,
                'sale_category' => 'services',
            ],
            [
                'product_code' => 'SVC-003',
                'name' => 'Software Update & Optimization',
                'category_id' => $serviceCategory->id,
                'price' => 2000.00,
                'cost_price' => 0.00,
                'stock_quantity' => null,
                'sale_category' => 'services',
            ],
        ];

        $createdProducts = [];
        foreach ($products as $productData) {
            $saleCategory = $productData['sale_category'];
            unset($productData['sale_category']);
            
            $product = Product::firstOrCreate(
                ['product_code' => $productData['product_code']],
                $productData
            );
            $createdProducts[] = [
                'product' => $product,
                'sale_category' => $saleCategory,
            ];
        }

        // Create sample customers
        $customers = [];
        for ($i = 1; $i <= 20; $i++) {
            $customers[] = Customer::firstOrCreate(
                ['phone' => '07' . str_pad($i, 8, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Customer ' . $i,
                    'email' => 'customer' . $i . '@example.com',
                ]
            );
        }

        // Generate invoices for the past 14 months (to have comparison data)
        $startDate = Carbon::now()->subMonths(14)->startOfMonth();
        $endDate = Carbon::now();
        
        $invoiceNumber = Invoice::max('id') ?? 0;
        
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            // Generate 5-15 invoices per day with some randomness
            $invoicesPerDay = rand(3, 12);
            
            // Some days have fewer sales
            if (rand(1, 10) <= 2) {
                $invoicesPerDay = rand(0, 3);
            }

            for ($i = 0; $i < $invoicesPerDay; $i++) {
                $invoiceNumber++;
                $customer = $customers[array_rand($customers)];
                
                // Create invoice
                $invoice = Invoice::create([
                    'invoice_number' => 'INV-' . str_pad($invoiceNumber, 6, '0', STR_PAD_LEFT),
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'total_amount' => 0,
                    'total_cost' => 0,
                    'total_profit' => 0,
                    'discount' => rand(0, 100) <= 20 ? rand(100, 1000) : 0,
                    'warranty_period' => ['0', '3', '6'][array_rand(['0', '3', '6'])],
                    'payment_method' => ['cash', 'card'][array_rand(['cash', 'card'])],
                    'issued_date' => $currentDate->format('Y-m-d'),
                    'created_at' => $currentDate->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                    'updated_at' => $currentDate->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                ]);

                // Add 1-5 items to each invoice
                $itemCount = rand(1, 5);
                $totalAmount = 0;
                $totalCost = 0;
                $totalProfit = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $productInfo = $createdProducts[array_rand($createdProducts)];
                    $product = $productInfo['product'];
                    $saleCategory = $productInfo['sale_category'];
                    
                    $quantity = $saleCategory === 'phones' ? rand(1, 2) : rand(1, 5);
                    $itemTotal = $product->price * $quantity;
                    $itemCost = $product->cost_price * $quantity;
                    $itemProfit = $itemTotal - $itemCost;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product->id,
                        'description' => $product->name,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'cost_price' => $product->cost_price,
                        'total' => $itemTotal,
                        'profit' => $itemProfit,
                        'sale_category' => $saleCategory,
                        'created_at' => $invoice->created_at,
                        'updated_at' => $invoice->updated_at,
                    ]);

                    $totalAmount += $itemTotal;
                    $totalCost += $itemCost;
                    $totalProfit += $itemProfit;
                }

                // Apply discount to profit
                $totalProfit -= $invoice->discount;
                $totalAmount -= $invoice->discount;

                // Update invoice totals
                $invoice->update([
                    'total_amount' => $totalAmount,
                    'total_cost' => $totalCost,
                    'total_profit' => $totalProfit,
                ]);
            }

            $currentDate->addDay();
        }

        $this->command->info('Dashboard test data seeded successfully!');
        $this->command->info('Total invoices created: ' . Invoice::count());
        $this->command->info('Total invoice items created: ' . InvoiceItem::count());
    }
}
