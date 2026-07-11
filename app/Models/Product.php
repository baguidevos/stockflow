<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'barcode',
        'name',
        'slug',
        'description',
        'specifications',
        'purchase_price',
        'selling_price',
        'selling_price_with_tax',
        'tax_rate',
        'stock_quantity',
        'reserved_quantity',
        'min_stock_level',
        'max_stock_level',
        'unit',
        'weight',
        'dimensions',
        'reference',
        'type',
        'has_variants',
        'variant_min_price',
        'variant_max_price',
        'stock_status',
        'is_active',
        'track_inventory',
    ];

    protected $casts = [
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
    ];

    use SoftDeletes;

    public function category(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function brand(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    public function variants(): Relations\HasMany
    {
        return $this->hasMany('App\Models\ProductVariant');
    }

    public function stockMovements(): Relations\HasMany
    {
        return $this->hasMany('App\Models\StockMovement');
    }

    public function alerts(): Relations\HasMany
    {
        return $this->hasMany('App\Models\Alert');
    }

    public function orderItems(): Relations\HasMany
    {
        return $this->hasMany('App\Models\OrderItem');
    }

    public function configurations(): Relations\HasMany
    {
        return $this->hasMany('App\Models\ProductConfiguration');
    }

    public function defaultVariant(): Relations\HasOne
    {
        return $this->hasOne('App\Models\ProductVariant');
    }

    public function attributeGroups(): Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\AttributeGroup', 'attribute_group');
    }

    public function media(): Relations\MorphMany
    {
        return $this->morphMany('App\Models\Media', 'modelable');
    }

    public const TYPE_SIMPLE = 'TYPE_SIMPLE';

    public const TYPE_CONFIGURABLE = 'TYPE_CONFIGURABLE';

    public function scopesimple($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopeconfigurable($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopewithVariants($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopewithoutVariants($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopebyBrand($query)
    {
        // TODO: Implémenter le scope
    }

    public function getTotalVariantStockAttribute()
    {
        // TODO: Implémenter l'accessor
    }

    public function getMinPriceAttribute()
    {
        // TODO: Implémenter l'accessor
    }

    public function getMaxPriceAttribute()
    {
        // TODO: Implémenter l'accessor
    }

    public function generateVariantSku()
    {
        // TODO: Implémenter la méthode
    }

    public function hasVariantWithAttributes()
    {
        // TODO: Implémenter la méthode
    }

    // Médias
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(300)
            ->height(300)
            ->sharpen(10);

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(1200)
            ->sharpen(10);
    }

    // Accessors pour les médias
    public function getMainImageAttribute()
    {
        return $this->getFirstMediaUrl('images', 'thumbnail');
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('images');
    }

    // Computed: Quantité disponible
    public function getAvailableQuantityAttribute()
    {
        return $this->stock_quantity - $this->reserved_quantity;
    }

    // Mise à jour automatique du statut
    public function updateStockStatus(): void
    {
        if ($this->available_quantity <= 0) {
            $this->stock_status = 'out_of_stock';
        } elseif ($this->available_quantity <= $this->min_stock_level) {
            $this->stock_status = 'low_stock';
        } else {
            $this->stock_status = 'in_stock';
        }
        $this->saveQuietly();
    }

    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_status', 'low_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_status', 'out_of_stock');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('barcode', 'like', "%{$term}%");
        });
    }
}
