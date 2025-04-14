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
        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('customer')->nullable();
            $table->string('initial_payment');
            $table->string('balance');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('initial_payment');
            $table->string('discount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_payments');
    }
};
