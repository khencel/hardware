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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('customer_id');
            $table->string('discount')->nullable()->after('rate_type');
            $table->string('delivery_option')->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('customer_id');
            $table->string('discount')->nullable()->after('rate_type');
            $table->string('delivery_option')->nullable()->after('discount');
        });
    }
};
