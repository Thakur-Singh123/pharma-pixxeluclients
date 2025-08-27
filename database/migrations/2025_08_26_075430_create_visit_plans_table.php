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
            $table->string('plan_type'); // monthly, weekly
            $table->enum('visit_category', ['hospital', 'doctor', 'area', 'camp', 'event']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('created_by'); // manager id
            $table->unsignedBigInteger('assigned_to')->nullable(); // MR id
            $table->unsignedBigInteger('doctor_id')->nullable(); // MR id
            $table->boolean('is_locked')->default(false);
            $table->enum('status', ['open', 'interested', 'assigned', 'completed'])->default('open');
            $table->timestamps();
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
