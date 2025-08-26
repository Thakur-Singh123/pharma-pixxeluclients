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
        Schema::table('ta_da_records', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('remarks');
            $table->unsignedBigInteger('approved_by')->nullable()->after('status'); 
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->string('purpose_of_visit')->nullable()->after('approved_at');
            $table->string('attachment')->nullable()->after('purpose_of_visit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tada_records', function (Blueprint $table) {
            //
        });
    }
};
