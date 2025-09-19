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
            $table->string('referred_contact')->after('medical_history')->nullable();
            $table->string('preferred_doctor')->after('referred_contact')->nullable();
            $table->string('place_referred')->after('preferred_doctor')->nullable();
            $table->string('bill_amount')->after('place_referred')->nullable();
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
