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
        Schema::table('reservation_payments', function (Blueprint $table) {
            $table->string('transaction_number')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_number');
            $table->unsignedBigInteger('reservation_id');
        });
    }
};
