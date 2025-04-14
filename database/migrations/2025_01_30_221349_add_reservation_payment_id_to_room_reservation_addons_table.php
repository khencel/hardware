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
        Schema::table('room_reservation_addons', function (Blueprint $table) {
            $table->unsignedBigInteger('reservation_payment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_reservation_addons', function (Blueprint $table) {
            $table->dropColumn('reservation_payment_id');
        });
    }
};
