<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class AttributeValue extends Model
{
    use HasFactory;

    protected $table = 'attribute_values';

    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'color',
        'image',
        'sort_order',
        'is_active'
    ];


    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean'
    ];

    public function attribute(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Attribute', 'attribute_id');
    }

    public function variantValues(): Relations\HasMany
    {
        return $this->hasMany('App\Models\VariantAttributeValue');
    }

    public function variants(): Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\ProductVariant', 'variant');
    }

    public function scopeactive($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopeordered($query)
    {
        // TODO: Implémenter le scope
    }
}
