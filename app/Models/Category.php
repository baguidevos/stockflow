<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'icon',
        'color',
        'sort_order',
        'is_active'
    ];


    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean'
    ];

    public function parent(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function children(): Relations\HasMany
    {
        return $this->hasMany('App\Models\Category');
    }

    public function products(): Relations\HasMany
    {
        return $this->hasMany('App\Models\Product');
    }

    public function media(): Relations\MorphMany
    {
        return $this->morphMany('App\Models\Media', 'modelable');
    }

    public function scopeactive($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopeordered($query)
    {
        // TODO: Implémenter le scope
    }

    public function getBreadcrumbsAttribute()
    {
        // TODO: Implémenter l'accessor
    }
}
