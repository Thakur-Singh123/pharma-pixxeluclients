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
            $table->string('pin_code')->after('area_code')->nullable();
            $table->string('visit_date')->after('pin_code')->nullable();
            $table->LongText('comments')->after('visit_date')->nullable();
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
