<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()
                  ->after('category_id')
                  ->constrained('brands')->nullOnDelete();

            $table->enum('type', ['simple', 'configurable'])->default('simple')->after('is_active');
            $table->boolean('has_variants')->default(false)->after('type');

            $table->decimal('variant_min_price', 12, 4)->nullable()->after('selling_price');
            $table->decimal('variant_max_price', 12, 4)->nullable()->after('variant_min_price');

            $table->index('brand_id');
            $table->index('type');
            $table->index('has_variants');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn([
                'brand_id',
                'type',
                'has_variants',
                'variant_min_price',
                'variant_max_price',
            ]);
        });
    }
};