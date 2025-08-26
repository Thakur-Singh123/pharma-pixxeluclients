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
        Schema::create('visit_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manager_id');
            $table->unsignedBigInteger('mr_id')->nullable(); 
            $table->date('visit_date'); 
            $table->string('location'); 
            $table->string('doctor_id'); 
            $table->text('notes')->nullable(); 
            $table->enum('status', ['planned', 'completed', 'cancelled'])->default('planned'); 
            $table->timestamps();

            // foreign keys
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mr_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_plans');
    }
};
