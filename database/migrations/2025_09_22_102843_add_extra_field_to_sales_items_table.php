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
        Schema::table('sales_items', function (Blueprint $table) {
            $table->string('salt_name')->after('sale_id')->nullable();
            $table->string('brand_name')->after('salt_name')->nullable();
            $table->string('type')->after('brand_name')->nullable();
            $table->string('company')->after('type')->nullable();
            $table->string('margin')->after('sale_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_items', function (Blueprint $table) {
            //
        });
    }
};
