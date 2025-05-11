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
        Schema::table('foods', function (Blueprint $table) {
            $table->integer('wholesale_price')->nullable()->after('cost_price');
            $table->integer('retail_price')->nullable()->after('wholesale_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->integer('wholesale_price')->nullable()->after('cost_price');
            $table->integer('retail_price')->nullable()->after('wholesale_price');
        });
    }
};
