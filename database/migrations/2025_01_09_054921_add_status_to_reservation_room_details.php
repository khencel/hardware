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
        Schema::table('reservation_room_details', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->string('pax')->nullable();
            $table->string('guest')->nullable();
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->double('total_amount')->default(0);
            $table->string('total_pax')->nullable();
            $table->string('total_guest')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_room_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('total');
            $table->dropColumn('total_pax');
            $table->dropColumn('total_guest');
        });
    }
};
