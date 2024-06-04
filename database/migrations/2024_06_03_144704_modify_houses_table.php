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
        Schema::table('houses', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->after('manager_id');
            $table->foreign('owner_id')->references('id')->on('users');
            // drop column manager_id and foreign key
            $table->dropForeign(['manager_id']);
            $table->dropColumn('manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            //
        });
    }
};
