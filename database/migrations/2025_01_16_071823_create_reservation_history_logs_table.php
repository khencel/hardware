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
        Schema::create('reservation_history_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();          
            $table->string('action');   
            $table->string('message')->nullable();            
            $table->longText('old_data')->nullable();
            $table->longText('new_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_history_logs');
    }
};
