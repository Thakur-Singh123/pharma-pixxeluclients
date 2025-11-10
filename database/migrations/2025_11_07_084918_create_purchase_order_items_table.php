<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('purchase_order_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('purchase_order_id');
      $table->string('product_name');
      $table->string('type')->nullable();
      $table->decimal('quantity', 12, 2)->default(1);
      $table->decimal('price', 12, 2)->default(0);
      $table->enum('discount_type', ['flat','percent'])->default('flat');
      $table->decimal('discount_value', 12, 2)->default(0);
      $table->decimal('line_total', 12, 2)->default(0);
      $table->timestamps();

      $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
    });
  }

  public function down(): void {
    Schema::dropIfExists('purchase_order_items');
  }
};