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
        Schema::create('room_reservation_addons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_reservation_details_id');
            $table->unsignedBigInteger('addon_id');
            $table->double('addon_price')->nullable();
            $table->string('addon_name')->nullable();
            $table->longText('addon_details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_reservation_addons');
    }
};
