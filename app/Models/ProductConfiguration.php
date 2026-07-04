<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class ProductConfiguration extends Model
{
    use HasFactory;

    protected $table = 'product_configurations';

    protected $fillable = [
        'product_id',
        'attribute_combination',
        'variant_id',
        'price_adjustment',
        'is_available'
    ];


    protected $casts = [
        'attribute_combination' => 'array',
        'price_adjustment' => 'decimal:4',
        'is_available' => 'boolean'
    ];

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function variant(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ProductVariant', 'variant_id');
    }

    public function scopeavailable($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopeforCombination($query)
    {
        // TODO: Implémenter le scope
    }

    public function getCombinationKeyAttribute()
    {
        // TODO: Implémenter l'accessor
    }
}
