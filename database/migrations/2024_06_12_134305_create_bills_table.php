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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained();
            $table->integer('total_money');
            $table->string('month', 7);
            $table->tinyInteger('status')->default(0)->comment('0: unpaid, 1: paid');
            $table->date('paid_date')->nullable();
            $table->string('note')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
