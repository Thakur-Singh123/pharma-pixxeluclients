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
        Schema::table('mr_daily_reports', function (Blueprint $table) {
            $table->string('doctor_id')->after('mr_id')->nullable();
            $table->LongText('area_name')->after('report_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mr_daily_reports', function (Blueprint $table) {
            //
        });
    }
};
