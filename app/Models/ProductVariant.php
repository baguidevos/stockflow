<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'name',
        'price_adjustment',
        'purchase_price_adjustment',
        'selling_price',
        'purchase_price',
        'stock_quantity',
        'reserved_quantity',
        'min_stock_level',
        'stock_status',
        'is_default',
        'is_active',
        'image',
        'custom_attributes'
    ];


    protected $casts = [
        'price_adjustment' => 'decimal:4',
        'purchase_price_adjustment' => 'decimal:4',
        'selling_price' => 'decimal:4',
        'purchase_price' => 'decimal:4',
        'stock_quantity' => 'decimal:3',
        'reserved_quantity' => 'decimal:3',
        'min_stock_level' => 'decimal:3',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'custom_attributes' => 'array'
    ];

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function attributeValues(): Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\AttributeValue', 'attribute_value');
    }

    public function variantValues(): Relations\HasMany
    {
        return $this->hasMany('App\Models\VariantAttributeValue');
    }

    public function stockMovements(): Relations\HasMany
    {
        return $this->hasMany('App\Models\StockMovement');
    }

    public function media(): Relations\MorphMany
    {
        return $this->morphMany('App\Models\Media', 'modelable');
    }

    public function scopeactive($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopeinStock($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopedefault($query)
    {
        // TODO: Implémenter le scope
    }

    public function getAvailableQuantityAttribute()
    {
        // TODO: Implémenter l'accessor
    }

    public function getFinalSellingPriceAttribute()
    {
        // TODO: Implémenter l'accessor
    }

    public function getFinalPurchasePriceAttribute()
    {
        // TODO: Implémenter l'accessor
    }

    public function getAttributesArrayAttribute()
    {
        // TODO: Implémenter l'accessor
    }

    public function updateStockStatus()
    {
        // TODO: Implémenter la méthode
    }

    public function matchesAttributes()
    {
        // TODO: Implémenter la méthode
    }
}
