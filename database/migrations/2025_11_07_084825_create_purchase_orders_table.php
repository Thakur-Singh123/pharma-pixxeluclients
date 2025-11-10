<?php

// database/migrations/xxxx_xx_xx_create_purchase_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('purchase_orders', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('purchase_manager_id');
      $table->unsignedBigInteger('vendor_id');
      $table->date('order_date');
      $table->text('notes')->nullable();
      $table->decimal('subtotal', 12, 2)->default(0);
      $table->decimal('discount_total', 12, 2)->default(0);
      $table->decimal('grand_total', 12, 2)->default(0);
      $table->timestamps();

      $table->foreign('purchase_manager_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  public function down(): void {
    Schema::dropIfExists('purchase_orders');
  }
};
