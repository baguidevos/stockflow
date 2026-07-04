<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'slug',
        'contact_person',
        'email',
        'phone',
        'mobile',
        'address',
        'city',
        'postal_code',
        'country',
        'siret',
        'tva_intracom',
        'notes',
        'rating',
        'is_active'
    ];


    protected $casts = [
        'rating' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    use SoftDeletes;

    public function orders(): Relations\HasMany
    {
        return $this->hasMany('App\Models\SupplierOrder');
    }

    public function media(): Relations\MorphMany
    {
        return $this->morphMany('App\Models\Media', 'modelable');
    }

    public function scopeactive($query)
    {
        // TODO: Implémenter le scope
    }

    public function getFullAddressAttribute()
    {
        // TODO: Implémenter l'accessor
    }
}
