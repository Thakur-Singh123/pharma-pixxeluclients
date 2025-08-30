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
            $table->foreignId('mr_id')->constrained('users')->onDelete('cascade');  
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade')->nullable(); 
            $table->string('visit_type')->after('doctor_id')->nullable();  // First Visit / Follow-up
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
