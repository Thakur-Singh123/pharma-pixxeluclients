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
        Schema::table('counselor_patients', function (Blueprint $table) {
              $table->enum('booking_done', ['yes', 'no', 'on_hold'])
                  ->default('no')
                  ->change();

            $table->text('booking_reason')->nullable();

            $table->decimal('estimated_amount', 10, 2)->nullable();

            $table->string('attachment')->nullable();

            $table->date('booking_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counselor_patients', function (Blueprint $table) {
            //
        });
    }
};
