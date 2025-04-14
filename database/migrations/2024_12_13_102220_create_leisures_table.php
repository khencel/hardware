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
        Schema::create('leisures', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('type')->nullable();
            $table->double('price_rate');
            $table->string('counter')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('image')->nullable();
            $table->boolean('availability')->defaultTrue();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leisures');
    }
};
