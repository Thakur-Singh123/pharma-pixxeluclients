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
        Schema::create('visit_plan_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_plan_id');
            $table->unsignedBigInteger('mr_id');
            $table->enum('status', ['assigned', 'approved', 'rejected'])->default('assigned');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('visit_plan_id')->references('id')->on('visit_plans')->onDelete('cascade');
            $table->foreign('mr_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_plan_assignments');
    }
};
