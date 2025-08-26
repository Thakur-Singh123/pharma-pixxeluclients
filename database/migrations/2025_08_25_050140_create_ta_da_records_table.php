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
        Schema::create('ta_da_records', function (Blueprint $table) {
            $table->id();
            $table->string('mr_id')->nullable();
            $table->date('travel_date')->nullable();            
            $table->string('place_visited')->nullable();         
            $table->decimal('distance_km', 8, 2)->nullable();    
            $table->decimal('ta_rate', 8, 2)->default(0)->nullable(); 
            $table->decimal('ta_amount', 10, 2)->default(0)->nullable(); 
            $table->decimal('da_amount', 10, 2)->default(0)->nullable();
            $table->decimal('total_amount', 12, 2)->default(0)->nullable(); 
            $table->string('mode_of_travel')->nullable()->nullable(); 
            $table->text('remarks')->nullable()->nullable();     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_da_records');
    }
};
