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
        Schema::table('referred_patients', function (Blueprint $table) {
            $table->string('doctor_id')->after('manager_id')->nullable();
            $table->string('dob')->after('disease')->nullable();
            $table->string('gender')->after('dob')->nullable();
            $table->string('emergency_contact')->after('gender')->nullable();
            $table->string('blood_group')->after('emergency_contact')->nullable();
            $table->LongText('medical_history')->after('referred_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referred_patients', function (Blueprint $table) {
            //
        });
    }
};
