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
        Schema::create('client_category_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('label');   // Field Label (Email, Address)
            $table->string('name');    // Field Key (email, address)
            $table->enum('type', ['input', 'textarea']);
            $table->enum('input_type', ['text', 'number', 'url']);
            $table->enum('validation_type', ['name','contact','address','none'])->default('none');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_category_fields');
    }
};
