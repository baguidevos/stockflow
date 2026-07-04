<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()
                  ->constrained('categories')->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();

            $table->decimal('purchase_price', 12, 4)->default(0);
            $table->decimal('selling_price', 12, 4)->default(0);
            $table->decimal('selling_price_with_tax', 12, 4)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(20.00);

            $table->decimal('stock_quantity', 12, 3)->default(0);
            $table->decimal('reserved_quantity', 12, 3)->default(0);
            $table->decimal('available_quantity', 12, 3)->default(0);
            $table->decimal('min_stock_level', 12, 3)->default(0);
            $table->decimal('max_stock_level', 12, 3)->default(0);

            $table->string('unit', 50)->default('unité');
            $table->string('weight', 20)->nullable();
            $table->string('dimensions', 50)->nullable();
            $table->string('reference')->nullable();
            $table->string('brand')->nullable();

            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])
                  ->default('in_stock');
            $table->boolean('is_active')->default(true);
            $table->boolean('track_inventory')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('sku');
            $table->index('barcode');
            $table->index('slug');
            $table->index('category_id');
            $table->index('stock_status');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};