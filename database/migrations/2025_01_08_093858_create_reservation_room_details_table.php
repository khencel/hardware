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
        Schema::create('reservation_room_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('reservation_id');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->json('room_details');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('reservations', function (Blueprint $table){
            $table->dropColumn([
                'check_in_date',
                'check_out_date',
                'room_details',
                'category_id'
            ]);
        });

        Schema::dropIfExists('room_reservation');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_room_details');
    }
};
