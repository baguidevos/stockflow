<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'country',
        'contact_email',
        'contact_phone',
        'notes',
        'sort_order',
        'is_active'
    ];


    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean'
    ];

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

    public function getProductCountAttribute()
    {
        // TODO: Implémenter l'accessor
    }
}
