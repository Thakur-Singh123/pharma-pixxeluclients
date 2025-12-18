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
            $table->string('clinic_hospital_name')->nullable()->after('visit_type');
            $table->string('mobile', 20)->nullable()->after('clinic_hospital_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_visits', function (Blueprint $table) {
            $table->dropColumn(['clinic_hospital_name', 'mobile']);
        });
    }
};

