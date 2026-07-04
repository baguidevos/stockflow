<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();

            $table->enum('alert_type', [
                'low_stock',
                'out_of_stock',
                'overstock',
                'expiring_soon',
                'expired',
                'price_change',
                'supplier_delay',
            ]);

            $table->string('title');
            $table->text('message');
            $table->text('details')->nullable();

            $table->decimal('current_quantity', 12, 3)->nullable();
            $table->decimal('threshold_quantity', 12, 3)->nullable();

            $table->boolean('is_read')->default(false);
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();

            $table->string('recommended_action')->nullable();
            $table->decimal('recommended_quantity', 12, 3)->nullable();

            $table->enum('notification_channel', ['system', 'email', 'sms', 'push'])
                  ->default('system');
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('notification_sent_at')->nullable();

            $table->timestamps();

            $table->index('product_id');
            $table->index('alert_type');
            $table->index('is_read');
            $table->index('is_resolved');
            $table->index(['alert_type', 'is_resolved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};