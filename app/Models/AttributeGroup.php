<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class AttributeGroup extends Model
{
    use HasFactory;

    protected $table = 'attribute_groups';

    protected $fillable = [
        'name',
        'slug',
        'display_name',
        'description',
        'sort_order',
        'is_active'
    ];


    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean'
    ];

    public function attributes(): Relations\HasMany
    {
        return $this->hasMany('App\Models\Attribute');
    }

    public function products(): Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Product', 'product');
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
