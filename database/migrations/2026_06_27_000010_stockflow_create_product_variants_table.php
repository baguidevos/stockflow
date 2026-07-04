<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name');

            $table->decimal('price_adjustment', 12, 4)->default(0);
            $table->decimal('purchase_price_adjustment', 12, 4)->default(0);

            $table->decimal('selling_price', 12, 4)->nullable();
            $table->decimal('purchase_price', 12, 4)->nullable();

            $table->decimal('stock_quantity', 12, 3)->default(0);
            $table->decimal('reserved_quantity', 12, 3)->default(0);
            $table->decimal('min_stock_level', 12, 3)->default(0);

            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])
                  ->default('in_stock');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->string('image')->nullable();
            $table->json('custom_attributes')->nullable();

            $table->timestamps();

            $table->index('sku');
            $table->index('barcode');
            $table->index('product_id');
            $table->index('is_default');
            $table->index('is_active');
            $table->index('stock_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};