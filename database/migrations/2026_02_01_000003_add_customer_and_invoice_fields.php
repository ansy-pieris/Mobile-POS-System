<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('nic')->nullable()->after('phone');
        });

        // Add customer snapshot fields to invoices for walk-in customers
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('customer_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->string('customer_nic')->nullable()->after('customer_phone');
        });

        // Add warranty_months and discount fields to invoice_items
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->integer('warranty_months')->default(0)->after('quantity');
            $table->decimal('discount', 10, 2)->default(0)->after('warranty_months');
            $table->string('discount_type')->default('value')->after('discount'); // 'value' or 'percent'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('nic');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'customer_phone', 'customer_nic']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['warranty_months', 'discount', 'discount_type']);
        });
    }
};
