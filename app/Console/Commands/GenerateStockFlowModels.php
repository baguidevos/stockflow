<?php

/**
 * Script de génération automatique des modèles Eloquent
 * StockFlow - Gestion de Stock
 *
 * Usage: php artisan stockflow:generate-models
 */

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('stockflow:generate-models {--force : Écraser les fichiers existants}')]
#[Description('Génère automatiquement tous les modèles Eloquent pour StockFlow')]
class GenerateStockFlowModels extends Command
{
    protected string $modelsPath = 'app/Models';
    protected array $models = [];

    public function handle(): int
    {
        $this->info('🏗️  Génération des modèles StockFlow...');

        $this->defineModels();

        foreach ($this->models as $name => $config) {
            $this->generateModel($name, $config);
        }

        $this->info('✅ ' . count($this->models) . ' modèles traités avec succès !');

        return Command::SUCCESS;
    }

    protected function defineModels(): void
    {
        $this->models = [
            // ============ UTILISATEURS & AUTH ============
            'User' => [
                'table' => 'users',
                'fillable' => [
                    'name', 'email', 'password', 'is_active', 'phone', 'warehouse_id', 'avatar',
                ],
                'casts' => [
                    'is_active' => 'boolean',
                    'email_verified_at' => 'datetime',
                ],
                'hidden' => ['password', 'remember_token'],
                'traits' => [
                    'Illuminate\Notifications\Notifiable',
                    'Spatie\Permission\Traits\HasRoles',
                    'App\Traits\HasAvatar',
                ],
                'relations' => [
                    'belongsTo' => [
                        'warehouse' => 'Warehouse',
                    ],
                    'hasMany' => [
                        'createdMovements' => 'StockMovement',
                        'createdOrders' => 'SupplierOrder',
                        'resolvedAlerts' => 'Alert',
                    ],
                ],
                'media' => true,
                'custom' => [
                    'getInitialsAttribute',
                    'scopeActive',
                ],
            ],

            // ============ CATALOGUE ============
            'Warehouse' => [
                'table' => 'warehouses',
                'fillable' => [
                    'name', 'slug', 'address', 'city', 'postal_code', 'country',
                    'description', 'is_default', 'is_active',
                ],
                'casts' => [
                    'is_default' => 'boolean',
                    'is_active' => 'boolean',
                ],
                'traits' => [],
                'relations' => [
                    'hasMany' => [
                        'users' => 'User',
                        'stockMovements' => 'StockMovement',
                        'supplierOrders' => 'SupplierOrder',
                        'alerts' => 'Alert',
                    ],
                    'morphMany' => [
                        'media' => 'Media',
                    ],
                ],
                'softDeletes' => true,
                'custom' => [
                    'getFullAddressAttribute',
                    'scopeDefault',
                    'scopeActive',
                ],
            ],

            'Category' => [
                'table' => 'categories',
                'fillable' => [
                    'name', 'slug', 'description', 'parent_id', 'icon', 'color', 'sort_order', 'is_active',
                ],
                'casts' => [
                    'sort_order' => 'integer',
                    'is_active' => 'boolean',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'parent' => 'Category',
                    ],
                    'hasMany' => [
                        'children' => 'Category',
                        'products' => 'Product',
                    ],
                    'morphMany' => [
                        'media' => 'Media',
                    ],
                ],
                'custom' => [
                    'scopeActive',
                    'scopeOrdered',
                    'getBreadcrumbsAttribute',
                ],
            ],

            'Brand' => [
                'table' => 'brands',
                'fillable' => [
                    'name', 'slug', 'description', 'logo', 'website', 'country',
                    'contact_email', 'contact_phone', 'notes', 'sort_order', 'is_active',
                ],
                'casts' => [
                    'sort_order' => 'integer',
                    'is_active' => 'boolean',
                ],
                'traits' => [],
                'relations' => [
                    'hasMany' => [
                        'products' => 'Product',
                    ],
                    'morphMany' => [
                        'media' => 'Media',
                    ],
                ],
                'custom' => [
                    'scopeActive',
                    'scopeOrdered',
                    'getProductCountAttribute',
                ],
            ],

            // ============ PRODUITS ============
            'Product' => [
                'table' => 'products',
                'fillable' => [
                    'category_id', 'brand_id', 'sku', 'barcode', 'name', 'slug',
                    'description', 'specifications', 'purchase_price', 'selling_price',
                    'selling_price_with_tax', 'tax_rate', 'stock_quantity', 'reserved_quantity',
                    'min_stock_level', 'max_stock_level', 'unit', 'weight', 'dimensions',
                    'reference', 'type', 'has_variants', 'variant_min_price', 'variant_max_price',
                    'stock_status', 'is_active', 'track_inventory',
                ],
                'casts' => [
                    'specifications' => 'array',
                    'purchase_price' => 'decimal:4',
                    'selling_price' => 'decimal:4',
                    'selling_price_with_tax' => 'decimal:4',
                    'tax_rate' => 'decimal:2',
                    'stock_quantity' => 'decimal:3',
                    'reserved_quantity' => 'decimal:3',
                    'available_quantity' => 'decimal:3',
                    'min_stock_level' => 'decimal:3',
                    'max_stock_level' => 'decimal:3',
                    'variant_min_price' => 'decimal:4',
                    'variant_max_price' => 'decimal:4',
                    'has_variants' => 'boolean',
                    'is_active' => 'boolean',
                    'track_inventory' => 'boolean',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'category' => 'Category',
                        'brand' => 'Brand',
                    ],
                    'hasMany' => [
                        'variants' => 'ProductVariant',
                        'stockMovements' => 'StockMovement',
                        'alerts' => 'Alert',
                        'orderItems' => 'OrderItem',
                        'configurations' => 'ProductConfiguration',
                    ],
                    'hasOne' => [
                        'defaultVariant' => 'ProductVariant',
                    ],
                    'belongsToMany' => [
                        'attributeGroups' => 'AttributeGroup',
                    ],
                    'morphMany' => [
                        'media' => 'Media',
                    ],
                ],
                'softDeletes' => true,
                'media' => true,
                'custom' => [
                    'TYPE_SIMPLE',
                    'TYPE_CONFIGURABLE',
                    'getAvailableQuantityAttribute',
                    'getTotalVariantStockAttribute',
                    'getMinPriceAttribute',
                    'getMaxPriceAttribute',
                    'updateStockStatus',
                    'generateVariantSku',
                    'hasVariantWithAttributes',
                    'scopeSimple',
                    'scopeConfigurable',
                    'scopeWithVariants',
                    'scopeWithoutVariants',
                    'scopeByBrand',
                    'scopeInStock',
                    'scopeLowStock',
                    'scopeOutOfStock',
                ],
            ],

            // ============ VARIANTS ============
            'AttributeGroup' => [
                'table' => 'attribute_groups',
                'fillable' => [
                    'name', 'slug', 'display_name', 'description', 'sort_order', 'is_active',
                ],
                'casts' => [
                    'sort_order' => 'integer',
                    'is_active' => 'boolean',
                ],
                'traits' => [],
                'relations' => [
                    'hasMany' => [
                        'attributes' => 'Attribute',
                    ],
                    'belongsToMany' => [
                        'products' => 'Product',
                    ],
                ],
                'custom' => [
                    'scopeActive',
                    'scopeOrdered',
                ],
            ],

            'Attribute' => [
                'table' => 'attributes',
                'fillable' => [
                    'attribute_group_id', 'name', 'slug', 'type', 'color', 'value',
                    'sort_order', 'is_filterable', 'is_required', 'is_active',
                ],
                'casts' => [
                    'sort_order' => 'integer',
                    'is_filterable' => 'boolean',
                    'is_required' => 'boolean',
                    'is_active' => 'boolean',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'group' => 'AttributeGroup',
                    ],
                    'hasMany' => [
                        'values' => 'AttributeValue',
                        'variantValues' => 'VariantAttributeValue',
                    ],
                ],
                'custom' => [
                    'TYPE_TEXT',
                    'TYPE_SELECT',
                    'TYPE_COLOR',
                    'TYPE_SIZE',
                    'TYPE_NUMBER',
                    'scopeActive',
                    'scopeFilterable',
                    'scopeRequired',
                    'scopeOfType',
                ],
            ],

            'AttributeValue' => [
                'table' => 'attribute_values',
                'fillable' => [
                    'attribute_id', 'value', 'slug', 'color', 'image', 'sort_order', 'is_active',
                ],
                'casts' => [
                    'sort_order' => 'integer',
                    'is_active' => 'boolean',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'attribute' => 'Attribute',
                    ],
                    'hasMany' => [
                        'variantValues' => 'VariantAttributeValue',
                    ],
                    'belongsToMany' => [
                        'variants' => 'ProductVariant',
                    ],
                ],
                'custom' => [
                    'scopeActive',
                    'scopeOrdered',
                ],
            ],

            'ProductVariant' => [
                'table' => 'product_variants',
                'fillable' => [
                    'product_id', 'sku', 'barcode', 'name', 'price_adjustment',
                    'purchase_price_adjustment', 'selling_price', 'purchase_price',
                    'stock_quantity', 'reserved_quantity', 'min_stock_level',
                    'stock_status', 'is_default', 'is_active', 'image', 'custom_attributes',
                ],
                'casts' => [
                    'price_adjustment' => 'decimal:4',
                    'purchase_price_adjustment' => 'decimal:4',
                    'selling_price' => 'decimal:4',
                    'purchase_price' => 'decimal:4',
                    'stock_quantity' => 'decimal:3',
                    'reserved_quantity' => 'decimal:3',
                    'min_stock_level' => 'decimal:3',
                    'is_default' => 'boolean',
                    'is_active' => 'boolean',
                    'custom_attributes' => 'array',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'product' => 'Product',
                    ],
                    'belongsToMany' => [
                        'attributeValues' => 'AttributeValue',
                    ],
                    'hasMany' => [
                        'variantValues' => 'VariantAttributeValue',
                        'stockMovements' => 'StockMovement',
                    ],
                    'morphMany' => [
                        'media' => 'Media',
                    ],
                ],
                'media' => true,
                'custom' => [
                    'getAvailableQuantityAttribute',
                    'getFinalSellingPriceAttribute',
                    'getFinalPurchasePriceAttribute',
                    'getAttributesArrayAttribute',
                    'updateStockStatus',
                    'matchesAttributes',
                    'scopeActive',
                    'scopeInStock',
                    'scopeDefault',
                ],
            ],

            'VariantAttributeValue' => [
                'table' => 'variant_attribute_values',
                'fillable' => [
                    'variant_id', 'attribute_id', 'attribute_value_id',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'variant' => 'ProductVariant',
                        'attribute' => 'Attribute',
                        'attributeValue' => 'AttributeValue',
                    ],
                ],
            ],

            'ProductConfiguration' => [
                'table' => 'product_configurations',
                'fillable' => [
                    'product_id', 'attribute_combination', 'variant_id', 'price_adjustment', 'is_available',
                ],
                'casts' => [
                    'attribute_combination' => 'array',
                    'price_adjustment' => 'decimal:4',
                    'is_available' => 'boolean',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'product' => 'Product',
                        'variant' => 'ProductVariant',
                    ],
                ],
                'custom' => [
                    'scopeAvailable',
                    'scopeForCombination',
                    'getCombinationKeyAttribute',
                ],
            ],

            // ============ MOUVEMENTS ============
            'StockMovement' => [
                'table' => 'stock_movements',
                'fillable' => [
                    'product_id', 'variant_id', 'warehouse_id', 'movement_type',
                    'quantity', 'quantity_before', 'quantity_after', 'unit_price',
                    'reference_number', 'notes', 'created_by', 'occurred_at',
                ],
                'casts' => [
                    'quantity' => 'decimal:3',
                    'quantity_before' => 'decimal:3',
                    'quantity_after' => 'decimal:3',
                    'unit_price' => 'decimal:4',
                    'occurred_at' => 'datetime',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'product' => 'Product',
                        'variant' => 'ProductVariant',
                        'warehouse' => 'Warehouse',
                        'creator' => 'User',
                    ],
                ],
                'custom' => [
                    'MOVEMENT_PURCHASE',
                    'MOVEMENT_SALE',
                    'MOVEMENT_ADJUSTMENT',
                    'MOVEMENT_TRANSFER_IN',
                    'MOVEMENT_TRANSFER_OUT',
                    'MOVEMENT_RETURN',
                    'MOVEMENT_RETURN_TO_SUPPLIER',
                    'MOVEMENT_DAMAGE',
                    'MOVEMENT_INVENTORY',
                    'scopeOfType',
                    'scopeByProduct',
                    'scopeByDateRange',
                ],
            ],

            // ============ COMMANDES ============
            'Supplier' => [
                'table' => 'suppliers',
                'fillable' => [
                    'name', 'slug', 'contact_person', 'email', 'phone', 'mobile',
                    'address', 'city', 'postal_code', 'country', 'siret', 'tva_intracom',
                    'notes', 'rating', 'is_active',
                ],
                'casts' => [
                    'rating' => 'decimal:2',
                    'is_active' => 'boolean',
                ],
                'traits' => [],
                'softDeletes' => true,
                'relations' => [
                    'hasMany' => [
                        'orders' => 'SupplierOrder',
                    ],
                    'morphMany' => [
                        'media' => 'Media',
                    ],
                ],
                'custom' => [
                    'scopeActive',
                    'getFullAddressAttribute',
                ],
            ],

            'SupplierOrder' => [
                'table' => 'supplier_orders',
                'fillable' => [
                    'supplier_id', 'warehouse_id', 'order_number', 'order_date',
                    'expected_date', 'received_date', 'status', 'subtotal',
                    'shipping_cost', 'tax_amount', 'total_amount', 'notes', 'internal_notes',
                    'created_by', 'approved_by', 'approved_at',
                ],
                'casts' => [
                    'order_date' => 'date',
                    'expected_date' => 'date',
                    'received_date' => 'date',
                    'subtotal' => 'decimal:4',
                    'shipping_cost' => 'decimal:4',
                    'tax_amount' => 'decimal:4',
                    'total_amount' => 'decimal:4',
                    'approved_at' => 'datetime',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'supplier' => 'Supplier',
                        'warehouse' => 'Warehouse',
                        'creator' => 'User',
                        'approver' => 'User', // Plus de collision de clé ici !
                    ],
                    'hasMany' => [
                        'items' => 'OrderItem',
                        'stockMovements' => 'StockMovement',
                    ],
                ],
                'custom' => [
                    'STATUS_DRAFT',
                    'STATUS_SENT',
                    'STATUS_CONFIRMED',
                    'STATUS_PARTIAL',
                    'STATUS_RECEIVED',
                    'STATUS_CANCELLED',
                    'scopeByStatus',
                    'scopeBySupplier',
                    'scopePending',
                    'calculateTotals',
                ],
            ],

            'OrderItem' => [
                'table' => 'order_items',
                'fillable' => [
                    'order_id', 'product_id', 'product_sku', 'product_name',
                    'ordered_quantity', 'received_quantity', 'returned_quantity',
                    'unit_price', 'tax_rate', 'line_total', 'notes',
                ],
                'casts' => [
                    'ordered_quantity' => 'decimal:3',
                    'received_quantity' => 'decimal:3',
                    'returned_quantity' => 'decimal:3',
                    'unit_price' => 'decimal:4',
                    'tax_rate' => 'decimal:2',
                    'line_total' => 'decimal:4',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'order' => 'SupplierOrder',
                        'product' => 'Product',
                    ],
                ],
                'custom' => [
                    'getRemainingQuantityAttribute',
                    'getReceivePercentageAttribute',
                ],
            ],

            // ============ ALERTES ============
            'Alert' => [
                'table' => 'alerts',
                'fillable' => [
                    'product_id', 'variant_id', 'warehouse_id', 'alert_type', 'title',
                    'message', 'details', 'current_quantity', 'threshold_quantity',
                    'is_read', 'is_resolved', 'read_at', 'resolved_by', 'resolved_at',
                    'resolution_notes', 'recommended_action', 'recommended_quantity',
                    'notification_channel', 'notification_sent', 'notification_sent_at',
                ],
                'casts' => [
                    'details' => 'array',
                    'current_quantity' => 'decimal:3',
                    'threshold_quantity' => 'decimal:3',
                    'recommended_quantity' => 'decimal:3',
                    'is_read' => 'boolean',
                    'is_resolved' => 'boolean',
                    'read_at' => 'datetime',
                    'resolved_at' => 'datetime',
                    'notification_sent' => 'boolean',
                    'notification_sent_at' => 'datetime',
                ],
                'traits' => [],
                'relations' => [
                    'belongsTo' => [
                        'product' => 'Product',
                        'variant' => 'ProductVariant',
                        'warehouse' => 'Warehouse',
                        'resolver' => 'User',
                    ],
                ],
                'custom' => [
                    'TYPE_LOW_STOCK',
                    'TYPE_OUT_OF_STOCK',
                    'TYPE_OVERSTOCK',
                    'TYPE_EXPIRING_SOON',
                    'TYPE_EXPIRED',
                    'TYPE_PRICE_CHANGE',
                    'TYPE_SUPPLIER_DELAY',
                    'scopeUnread',
                    'scopeUnresolved',
                    'scopeByType',
                    'markAsRead',
                    'markAsResolved',
                ],
            ],

            // ============ AUDIT ============
            'ActivityLog' => [
                'table' => 'activity_logs',
                'fillable' => [
                    'user_id', 'user_type', 'action', 'entity_type', 'entity_id',
                    'description', 'old_values', 'new_values', 'ip_address', 'user_agent',
                ],
                'casts' => [
                    'old_values' => 'array',
                    'new_values' => 'array',
                ],
                'traits' => [],
                'relations' => [
                    'morphTo' => [
                        'user' => 'user',
                    ],
                ],
                'custom' => [
                    'logActivity',
                    'scopeByEntity',
                    'scopeByAction',
                ],
            ],
        ];
    }

    protected function generateModel(string $name, array $config): void
    {
        $path = app_path("Models/{$name}.php");

        if (File::exists($path) && !$this->option('force')) {
            $this->warn("⏭️  {$name} existe déjà, utilisez --force pour l'écraser.");
            return;
        }

        $namespace = 'App\\Models';
        $traits = $this->generateTraits($config['traits'] ?? []);
        $relations = $this->generateRelations($config['relations'] ?? []);
        $customMethods = $this->generateCustomMethods($config['custom'] ?? []);
        $fillable = $this->generateFillable($config, $config['fillable'] ?? []);
        $casts = $this->generateCasts($config['casts'] ?? []);
        $softDeletes = $this->generateSoftDeletes($config);

        $content = "<?php\n\n" .
            "namespace {$namespace};\n\n" .
            "use Illuminate\Database\Eloquent\Factories\HasFactory;\n" .
            "use Illuminate\Database\Eloquent\Model;\n" .
            $this->generateUses($config) . "\n\n" .
            "class {$name} extends Model\n" .
            "{\n" .
            "    use HasFactory{$traits};\n\n" .
            "    protected \$table = '{$config['table']}';\n\n" .
            $fillable . "\n" .
            $casts .
            $softDeletes . "\n\n" .
            $relations . "\n\n" .
            $customMethods . "\n" .
            "}\n";

        File::put($path, $content);
        $this->line("  ✅ {$name}");
    }

    protected function generateUses(array $config): string
    {
        $uses = [];

        if ($config['softDeletes'] ?? false) {
            $uses[] = 'use Illuminate\Database\Eloquent\SoftDeletes;';
        }

        if ($config['media'] ?? false) {
            $uses[] = 'use Spatie\MediaLibrary\HasMedia;';
            $uses[] = 'use Spatie\MediaLibrary\InteractsWithMedia;';
        }

        if (!empty($config['relations'])) {
            $uses[] = 'use Illuminate\Database\Eloquent\Relations;';
        }

        return !empty($uses) ? implode("\n", $uses) : '';
    }

    protected function generateTraits(array $traits): string
    {
        if (empty($traits)) {
            return '';
        }

        $formatted = array_map(fn($trait) => "\n    use \\{$trait};", $traits);
        return implode("", $formatted);
    }

    protected function generateSoftDeletes(array $config): string
    {
        if ($config['softDeletes'] ?? false) {
            return "\n\n    use SoftDeletes;";
        }
        return '';
    }

    protected function generateFillable(array $config, array $fillable): string
    {
        // Gère les tables sans timestamps si demandé
        $timestampsCode = isset($config['no_timestamps']) && $config['no_timestamps'] ? "    public \$timestamps = false;\n\n" : "";

        if (empty($fillable)) {
            return "{$timestampsCode}    protected \$fillable = [];";
        }

        $items = array_map(fn($f) => "        '{$f}'", $fillable);
        return "{$timestampsCode}    protected \$fillable = [\n" . implode(",\n", $items) . "\n    ];";
    }

    protected function generateCasts(array $casts): string
    {
        if (empty($casts)) {
            return '';
        }

        $items = [];
        foreach ($casts as $field => $type) {
            $items[] = "        '{$field}' => '{$type}'";
        }

        return "\n\n    protected \$casts = [\n" . implode(",\n", $items) . "\n    ];";
    }

    protected function generateRelations(array $relations): string
    {
        if (empty($relations)) {
            return '';
        }

        $code = [];

        foreach ($relations as $type => $items) {
            foreach ($items as $methodName => $model) {
                $returnType = "Relations\\" . ucfirst($type);
                $param = $this->getRelationParam($type, $model, $methodName);
                
                $code[] = "    public function {$methodName}(): {$returnType}\n    {\n        return \$this->{$type}({$param});\n    }";
            }
        }

        return implode("\n\n", $code);
    }

    protected function getRelationParam(string $type, string $model, string $methodName): string
    {
        $modelClass = "App\\Models\\{$model}";

        switch ($type) {
            case 'belongsTo':
                $fk = Str::snake($methodName) . '_id';
                return "'{$modelClass}', '{$fk}'";

            case 'hasOne':
            case 'hasMany':
                return "'{$modelClass}'";

            case 'belongsToMany':
                $table = Str::snake(Str::singular($methodName));
                return "'{$modelClass}', '{$table}'";

            case 'morphOne':
            case 'morphMany':
                return "'{$modelClass}', 'modelable'";

            case 'morphTo':
                return '';

            default:
                return "'{$modelClass}'";
        }
    }

    protected function generateCustomMethods(array $methods): string
    {
        if (empty($methods)) {
            return '';
        }

        $constants = [];
        $scopes = [];
        $accessors = [];
        $regularMethods = [];

        foreach ($methods as $method) {
            if (str_starts_with($method, 'scope')) {
                $scopes[] = $method;
            } elseif (str_starts_with($method, 'get') && str_contains($method, 'Attribute')) {
                $accessors[] = $method;
            } elseif (str_contains($method, '_')) {
                $constants[] = $method;
            } else {
                $regularMethods[] = $method;
            }
        }

        $code = [];

        if (!empty($constants)) {
            $code[] = $this->generateConstants($constants);
        }

        foreach ($scopes as $scope) {
            $code[] = $this->generateScope($scope);
        }

        foreach ($accessors as $accessor) {
            $code[] = $this->generateAccessor($accessor);
        }

        foreach ($regularMethods as $method) {
            $code[] = $this->generateMethod($method);
        }

        return implode("\n\n", $code);
    }

    protected function generateConstants(array $constants): string
    {
        $items = [];
        foreach ($constants as $const) {
            $parts = explode('_', strtolower($const));
            $value = strtoupper(implode('_', $parts));
            $items[] = "    public const {$const} = '{$value}';";
        }
        return implode("\n", $items);
    }

    protected function generateScope(string $scope): string
    {
        $name = Str::camel(substr($scope, 5));
        return "    public function scope{$name}(\$query)\n    {\n        // TODO: Implémenter le scope\n    }";
    }

    protected function generateAccessor(string $accessor): string
    {
        return "    public function {$accessor}()\n    {\n        // TODO: Implémenter l'accessor\n    }";
    }

    protected function generateMethod(string $method): string
    {
        return "    public function {$method}()\n    {\n        // TODO: Implémenter la méthode\n    }";
    }
}