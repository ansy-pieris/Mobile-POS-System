<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            // Rename 'quantity' to 'quantity_change' if it exists
            if (Schema::hasColumn('stock_logs', 'quantity')) {
                $table->renameColumn('quantity', 'quantity_change');
            }
            
            // Add missing columns
            if (!Schema::hasColumn('stock_logs', 'quantity_change')) {
                $table->integer('quantity_change')->after('type');
            }
            
            if (!Schema::hasColumn('stock_logs', 'stock_after')) {
                $table->integer('stock_after')->after('quantity_change');
            }
            
            if (!Schema::hasColumn('stock_logs', 'reference')) {
                $table->string('reference')->nullable()->after('stock_after');
            }
            
            if (!Schema::hasColumn('stock_logs', 'note')) {
                $table->text('note')->nullable()->after('reference');
            }
            
            // Make user_id nullable if it exists
            if (Schema::hasColumn('stock_logs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->change();
            }
        });
        
        // Update the type enum to include 'sale'
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->string('type', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            if (Schema::hasColumn('stock_logs', 'stock_after')) {
                $table->dropColumn('stock_after');
            }
            if (Schema::hasColumn('stock_logs', 'reference')) {
                $table->dropColumn('reference');
            }
            if (Schema::hasColumn('stock_logs', 'note')) {
                $table->dropColumn('note');
            }
            if (Schema::hasColumn('stock_logs', 'quantity_change')) {
                $table->renameColumn('quantity_change', 'quantity');
            }
        });
    }
};
