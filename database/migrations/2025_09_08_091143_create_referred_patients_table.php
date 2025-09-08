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
        Schema::create('referred_patients', function (Blueprint $table) {
            $table->id();
            $table->string('mr_id')->nullable();
            $table->string('manager_id')->nullable();
            $table->string('patient_name')->nullable();
            $table->string('contact_no')->nullable();
            $table->LongText('address')->nullable();
            $table->string('disease')->nullable();
            $table->string('referred_to')->nullable();
            $table->enum('status',['Active','Pending','Suspend'])->default('Active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referred_patients');
    }
};
