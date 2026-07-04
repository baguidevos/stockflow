<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->json('attribute_combination');

            $table->foreignId('variant_id')->nullable()
                  ->constrained('product_variants')
                  ->nullOnDelete();

            $table->decimal('price_adjustment', 12, 4)->default(0);
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            $table->unique(['product_id', 'attribute_combination'], 'unique_config');
            $table->index('product_id');
            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_configurations');
    }
};