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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->int('user_id')->nullable();
            $table->string('area_name')->nullable();
            $table->string('area_block')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('area_code')->nullable();
            $table->string('doctor_id')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('doctor_contact')->nullable();
            $table->string('location')->nullable();
            $table->string('picture')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('visit_type',['Doctor','Ngo','Asha','Religious','Places','Other'])->default('Doctor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
