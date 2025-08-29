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
        Schema::create('mr_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mr_id'); // kis MR ka report hai
            $table->date('report_date');         // report kis din ka hai
            $table->integer('total_visits')->default(0);
            $table->integer('patients_referred')->default(0);
            $table->text('notes')->nullable(); // koi extra detail
            $table->timestamps();

            $table->foreign('mr_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mr_daily_reports');
    }
};
