<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('client_category_fields', function (Blueprint $table) {
            $table->json('options')->nullable()->after('validation_type');
        });

        DB::statement("ALTER TABLE client_category_fields MODIFY COLUMN type ENUM('input', 'textarea', 'dropdown') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_category_fields', function (Blueprint $table) {
            $table->dropColumn('options');
        });

        DB::statement("ALTER TABLE client_category_fields MODIFY COLUMN type ENUM('input', 'textarea') NOT NULL");
    }
};
