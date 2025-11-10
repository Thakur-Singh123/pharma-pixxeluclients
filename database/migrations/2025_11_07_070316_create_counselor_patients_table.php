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
        Schema::create('counselor_patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('mobile_no', 20);
            $table->string('email')->nullable();
            $table->string('department');
            $table->string('uhid_no')->nullable();
            $table->decimal('booking_amount', 10, 2)->nullable();
            $table->enum('booking_done', ['Yes', 'No'])->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselor_patients');
    }
};
