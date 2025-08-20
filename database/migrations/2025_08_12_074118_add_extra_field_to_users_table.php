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
            $table->string('first_name')->after('remember_token')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('dob')->after('last_name')->nullable();
            $table->string('gender')->after('dob')->nullable();
            $table->string('mobile')->after('gender')->nullable();
            $table->LongText('address')->after('mobile')->nullable();
            $table->string('image')->after('address')->nullable();
            $table->enum('status',['Active','Pending','Suspend','Approved'])->default('Active')->after('image')->nullable();
            $table->enum('user_type',['Admin','Manager','MR'])->default('MR')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
