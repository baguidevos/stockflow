<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('stockflow:generate-migrations')]
#[Description('Génère toutes les migrations pour le projet StockFlow')]
class GenerateStockFlowMigrations extends Command
{
    /**
     * Execute the console command.
     */
    
    private $migrationPath;
    private $timestamp = 0;

    public function __construct()
    {
        parent::__construct();
        $this->migrationPath = database_path('migrations');
    }

    public function handle()
    {
        $this->info('🚀 Génération des migrations StockFlow...');

        // Nettoyer les anciennes migrations StockFlow
        $this->cleanOldMigrations();

        // Générer les migrations dans l'ordre
        $this->generateBrandsTable();
        $this->generateAttributeGroupsTable();
        $this->generateAttributesTable();
        $this->generateAttributeValuesTable();
        $this->generateUsersTable();
        $this->generateWarehousesTable();
        $this->generateCategoriesTable();
        $this->generateProductsTable();
        $this->generateAddBrandToProductsTable();
        $this->generateProductVariantsTable();
        $this->generateVariantAttributeValuesTable();
        $this->generateProductConfigurationsTable();
        $this->generateProductAttributeGroupsTable();
        $this->generateSuppliersTable();
        $this->generateStockMovementsTable();
        $this->generateSupplierOrdersTable();
        $this->generateOrderItemsTable();
        $this->generateAlertsTable();
        $this->generateActivityLogsTable();

        $this->info('✅ Toutes les migrations ont été générées avec succès!');
        $this->info('📝 Exécutez: php artisan migrate');
    }

    private function cleanOldMigrations()
    {
        $files = File::glob($this->migrationPath . '/*_stockflow_*.php');
        foreach ($files as $file) {
            File::delete($file);
        }
        $this->info('🧹 Anciennes migrations nettoyées');
    }

    private function getTimestamp()
    {
        $this->timestamp++;
        return date('Y_m_d_') . str_pad($this->timestamp, 6, '0', STR_PAD_LEFT);
    }

    private function createMigration($name, $content)
    {
        $timestamp = $this->getTimestamp();
        $filename = "{$timestamp}_stockflow_{$name}.php";
        $filepath = $this->migrationPath . '/' . $filename;

        File::put($filepath, $content);
        $this->info("✓ Migration créée: {$filename}");
    }

    private function generateBrandsTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
PHP;

        $this->createMigration('create_brands_table', $content);
    }

    private function generateAttributeGroupsTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_groups');
    }
};
PHP;

        $this->createMigration('create_attribute_groups_table', $content);
    }

    private function generateAttributesTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_group_id')
                  ->constrained('attribute_groups')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type', 20)->default('select');
            $table->string('color', 7)->nullable();
            $table->string('value')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_filterable')->default(true);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('attribute_group_id');
            $table->index('is_filterable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
PHP;

        $this->createMigration('create_attributes_table', $content);
    }

    private function generateAttributeValuesTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')
                  ->constrained('attributes')
                  ->cascadeOnDelete();
            $table->string('value');
            $table->string('slug');
            $table->string('color', 7)->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('attribute_id');
            $table->unique(['attribute_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};
PHP;

        $this->createMigration('create_attribute_values_table', $content);
    }

    private function generateUsersTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('email');
            $table->string('phone')->nullable()->after('is_active');
            $table->foreignId('warehouse_id')->nullable()
                  ->after('phone');
            $table->string('avatar')->nullable()->after('warehouse_id');
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'phone',
                'warehouse_id',
                'avatar',
                'remember_token'
            ]);
        });
    }
};
PHP;

        $this->createMigration('extend_users_table', $content);
    }

    private function generateWarehousesTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->default('France');
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
PHP;

        $this->createMigration('create_warehouses_table', $content);
    }

    private function generateCategoriesTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()
                  ->constrained('categories')->nullOnDelete();
            $table->string('icon')->nullable();
            $table->string('color', 7)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
PHP;

        $this->createMigration('create_categories_table', $content);
    }

    private function generateProductsTable()
    {
        $content = <<<'PHP'
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
PHP;

        $this->createMigration('create_products_table', $content);
    }

    private function generateAddBrandToProductsTable()
    {
        $content = <<<'PHP'
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
PHP;

        $this->createMigration('add_brand_to_products_table', $content);
    }

    private function generateProductVariantsTable()
    {
        $content = <<<'PHP'
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
PHP;

        $this->createMigration('create_product_variants_table', $content);
    }

    private function generateVariantAttributeValuesTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')
                  ->constrained('product_variants')
                  ->cascadeOnDelete();
            $table->foreignId('attribute_id')
                  ->constrained('attributes')
                  ->cascadeOnDelete();
            $table->foreignId('attribute_value_id')
                  ->constrained('attribute_values')
                  ->cascadeOnDelete();

            $table->unique(['variant_id', 'attribute_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_attribute_values');
    }
};
PHP;

        $this->createMigration('create_variant_attribute_values_table', $content);
    }

    private function generateProductConfigurationsTable()
    {
        $content = <<<'PHP'
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
PHP;

        $this->createMigration('create_product_configurations_table', $content);
    }

    private function generateProductAttributeGroupsTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->foreignId('attribute_group_id')
                  ->constrained('attribute_groups')
                  ->cascadeOnDelete();
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            $table->unique(['product_id', 'attribute_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute_groups');
    }
};
PHP;

        $this->createMigration('create_product_attribute_groups_table', $content);
    }

    private function generateSuppliersTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('contact_person')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->default('France');
            $table->string('siret', 20)->nullable();
            $table->string('tva_intracom', 20)->nullable();
            $table->text('notes')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
PHP;

        $this->createMigration('create_suppliers_table', $content);
    }

    private function generateStockMovementsTable()
    {
        $content = <<<'PHP'
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
PHP;

        $this->createMigration('create_stock_movements_table', $content);
    }

    private function generateSupplierOrdersTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();

            $table->string('order_number')->unique();
            $table->date('order_date');
            $table->date('expected_date')->nullable();
            $table->date('received_date')->nullable();

            $table->enum('status', [
                'draft',
                'sent',
                'confirmed',
                'partial',
                'received',
                'cancelled',
            ])->default('draft');

            $table->decimal('subtotal', 12, 4)->default(0);
            $table->decimal('shipping_cost', 12, 4)->default(0);
            $table->decimal('tax_amount', 12, 4)->default(0);
            $table->decimal('total_amount', 12, 4)->default(0);

            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index('order_number');
            $table->index('supplier_id');
            $table->index('status');
            $table->index('order_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_orders');
    }
};
PHP;

        $this->createMigration('create_supplier_orders_table', $content);
    }

    private function generateOrderItemsTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('supplier_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->string('product_sku')->nullable();
            $table->string('product_name')->nullable();

            $table->decimal('ordered_quantity', 12, 3);
            $table->decimal('received_quantity', 12, 3)->default(0);
            $table->decimal('returned_quantity', 12, 3)->default(0);

            $table->decimal('unit_price', 12, 4);
            $table->decimal('tax_rate', 5, 2)->default(20.00);
            $table->decimal('line_total', 12, 4)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
PHP;

        $this->createMigration('create_order_items_table', $content);
    }

    private function generateAlertsTable()
    {
        $content = <<<'PHP'
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
PHP;

        $this->createMigration('create_alerts_table', $content);
    }

    private function generateActivityLogsTable()
    {
        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_type')->nullable();

            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();

            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamp('created_at');

            $table->index(['entity_type', 'entity_id']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
PHP;

        $this->createMigration('create_activity_logs_table', $content);
    }
}

