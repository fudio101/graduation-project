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
        Schema::create('contracts_end', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained();
            $table->unsignedBigInteger('member_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('termination_date');
            $table->text('description')->nullable();
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts_end');
    }
};
