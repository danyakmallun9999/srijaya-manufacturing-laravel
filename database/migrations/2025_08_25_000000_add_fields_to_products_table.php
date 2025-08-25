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
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
            $table->integer('stock')->default(0)->after('image');
            $table->string('model')->nullable()->after('stock');
            $table->string('wood_type')->nullable()->after('model');
            $table->text('details')->nullable()->after('wood_type');
            $table->enum('product_category', ['tetap', 'custom'])->default('tetap')->after('details');
            $table->decimal('base_price', 15, 2)->nullable()->after('product_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'image',
                'stock', 
                'model',
                'wood_type',
                'details',
                'product_category',
                'base_price'
            ]);
        });
    }
};
