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
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('item_code')->unique()->after('id');
            $table->string('category')->nullable()->after('item_name');
            $table->decimal('unit_price', 10, 2)->default(0)->after('quantity');
            $table->decimal('total_cost', 10, 2)->default(0)->after('unit_price');
            $table->string('supplier')->nullable()->after('date_acquired');
            $table->string('location')->nullable()->after('supplier');
            $table->enum('status', ['in stock', 'out of stock', 'damaged'])
                ->default('in stock')
                ->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['item_code', 'category', 'unit_price', 'total_cost', 'supplier', 'location', 'status']);
        });
    }
};
