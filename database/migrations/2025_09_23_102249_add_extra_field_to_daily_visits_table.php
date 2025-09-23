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
        Schema::table('daily_visits', function (Blueprint $table) {
            $table->LongText('villages')->after('school_type')->nullable();
            $table->LongText('city')->after('villages')->nullable();
            $table->LongText('societies')->after('city')->nullable();
            $table->LongText('ngo')->after('societies')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_visits', function (Blueprint $table) {
            //
        });
    }
};
