<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to add cost tracking fields for KPI Dashboard
 * 
 * This migration adds:
 * - cost_price to products table (purchase/cost price of product)
 * - cost_price to invoice_items table (cost at time of sale)
 * - total_cost and total_profit to invoices table (aggregated totals)
 * - sale_category to invoice_items (phones, accessories, services)
 * - Indexes for optimized dashboard queries
 */
return new class extends Migration
{
    public function up(): void
    {
        // Add cost_price to products table
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->default(0)->after('price');
        });

        // Add cost tracking fields to invoice_items table
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->default(0)->after('price');
            $table->decimal('total', 10, 2)->default(0)->after('cost_price');
            $table->decimal('profit', 10, 2)->default(0)->after('total');
            $table->enum('sale_category', ['phones', 'accessories', 'services'])->default('accessories')->after('profit');
        });

        // Add cost and profit totals to invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('total_cost', 10, 2)->default(0)->after('total_amount');
            $table->decimal('total_profit', 10, 2)->default(0)->after('total_cost');
        });

        // Add indexes for optimized dashboard queries
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('created_at', 'idx_invoices_created_at');
            $table->index(['created_at', 'total_amount'], 'idx_invoices_kpi');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->index('created_at', 'idx_invoice_items_created_at');
            $table->index(['sale_category', 'created_at'], 'idx_invoice_items_category');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropIndex('idx_invoice_items_created_at');
            $table->dropIndex('idx_invoice_items_category');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_created_at');
            $table->dropIndex('idx_invoices_kpi');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['total_cost', 'total_profit']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'total', 'profit', 'sale_category']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });
    }
};
