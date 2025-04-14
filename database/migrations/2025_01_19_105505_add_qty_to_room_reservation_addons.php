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
            $table->integer('qty')->default(1);
            $table->dropColumn('room_reservation_details_id');
            $table->unsignedBigInteger('reservation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_reservation_addons', function (Blueprint $table) {
            $table->dropColumn(['qty', 'reservation_id']);
            $table->unsignedBigInteger('room_reservation_details_id');
        });
    }
};
