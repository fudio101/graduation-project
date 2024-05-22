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
        Schema::create('electric_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained();
            $table->tinyInteger('status')->default(0)->comment('0: quantity, 1: step');
            $table->integer('quantity')->default(0);
            $table->json('step')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electric_managers');
    }
};
