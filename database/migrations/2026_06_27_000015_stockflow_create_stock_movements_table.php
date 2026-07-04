<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();

            $table->enum('movement_type', [
                'purchase',
                'sale',
                'adjustment',
                'transfer_in',
                'transfer_out',
                'return',
                'return_to_supplier',
                'damage',
                'inventory',
            ]);

            $table->decimal('quantity', 12, 3);
            $table->decimal('quantity_before', 12, 3)->default(0);
            $table->decimal('quantity_after', 12, 3)->default(0);

            $table->decimal('unit_price', 12, 4)->default(0);

            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('occurred_at')->useCurrent();

            $table->timestamps();

            $table->index('product_id');
            $table->index('warehouse_id');
            $table->index('movement_type');
            $table->index('reference_number');
            $table->index('occurred_at');
            $table->index(['product_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};