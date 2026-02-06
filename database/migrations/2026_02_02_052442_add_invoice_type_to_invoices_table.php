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
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('invoice_type', ['product', 'service'])->default('product')->after('invoice_number');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            // For service invoices, we don't need a product_id
            $table->bigInteger('product_id')->unsigned()->nullable()->change();
            // Add service-specific fields
            $table->string('service_type')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_type');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });
    }
};
