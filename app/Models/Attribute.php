<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';

    protected $fillable = [
        'attribute_group_id',
        'name',
        'slug',
        'type',
        'color',
        'value',
        'sort_order',
        'is_filterable',
        'is_required',
        'is_active'
    ];


    protected $casts = [
        'sort_order' => 'integer',
        'is_filterable' => 'boolean',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function group(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\AttributeGroup', 'group_id');
    }

    public function values(): Relations\HasMany
    {
        return $this->hasMany('App\Models\AttributeValue');
    }

    public function variantValues(): Relations\HasMany
    {
        return $this->hasMany('App\Models\VariantAttributeValue');
    }

    public const TYPE_TEXT = 'TYPE_TEXT';
    public const TYPE_SELECT = 'TYPE_SELECT';
    public const TYPE_COLOR = 'TYPE_COLOR';
    public const TYPE_SIZE = 'TYPE_SIZE';
    public const TYPE_NUMBER = 'TYPE_NUMBER';

    public function scopeactive($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopefilterable($query)
    {
        // TODO: Implémenter le scope
    }

    public function scoperequired($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopeofType($query)
    {
        // TODO: Implémenter le scope
    }
}
