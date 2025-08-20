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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('phone')->nullable();
            $table->string('territory')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('employee_code')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn([
                'phone', 'territory', 'city', 'state',
                'joining_date', 'employee_code'
            ]);
        });
    }
};
