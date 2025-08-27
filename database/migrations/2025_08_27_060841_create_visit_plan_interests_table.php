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
       Schema::create('visit_plan_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_plan_id');
            $table->unsignedBigInteger('mr_id'); // rep ka user id
            $table->enum('status', ['interested', 'not_interested'])->default('interested');
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
        Schema::dropIfExists('visit_plan_interests');
    }
};
