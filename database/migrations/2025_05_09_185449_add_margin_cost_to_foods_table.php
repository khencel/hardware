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
            $table->integer('margin_percentage')->nullable()->after('price');
            $table->decimal('cost_price', 10, 2)->nullable()->after('margin_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->integer('margin_percentage')->nullable()->after('price');
            $table->decimal('cost_price', 10, 2)->nullable()->after('margin_percentage');
        });
    }
};
