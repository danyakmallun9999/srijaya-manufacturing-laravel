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
        // database/migrations/..._create_orders_table.php
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->enum('product_type', ['tetap', 'custom']);
            $table->string('product_name'); // Untuk custom atau salinan nama produk tetap
            $table->text('product_specification')->nullable(); // Untuk detail produk custom
            $table->date('order_date');
            $table->date('deadline')->nullable();
            $table->integer('quantity');
            $table->string('status')->default('Draft');
            $table->decimal('total_price', 15, 2)->nullable(); // Harga Jual
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
