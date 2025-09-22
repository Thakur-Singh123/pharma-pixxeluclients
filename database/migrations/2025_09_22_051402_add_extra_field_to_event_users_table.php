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
        Schema::table('event_users', function (Blueprint $table) {
            $table->string('email')->after('name')->nullable();
            $table->string('kyc')->after('email')->nullable();
            $table->string('age')->after('kyc')->nullable();
            $table->string('sex')->after('age')->nullable();
            $table->string('pin_code')->after('phone')->nullable();
            $table->string('uid')->after('pin_code')->nullable();
            $table->LongText('disease')->after('uid')->nullable();
            $table->boolean('health_declare')->default(0)->after('disease');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_users', function (Blueprint $table) {
            //
        });
    }
};
