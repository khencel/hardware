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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->string('customer_name');
            $table->string('order_number')->unique();
            $table->dateTime('date');
            $table->json('items');
            $table->string('discount')->nullable();
            $table->string('delivery_option')->nullable();
            $table->string('reason')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
