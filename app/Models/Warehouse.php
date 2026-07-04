<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'slug',
        'address',
        'city',
        'postal_code',
        'country',
        'description',
        'is_default',
        'is_active'
    ];


    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    use SoftDeletes;

    public function users(): Relations\HasMany
    {
        return $this->hasMany('App\Models\User');
    }

    public function stockMovements(): Relations\HasMany
    {
        return $this->hasMany('App\Models\StockMovement');
    }

    public function supplierOrders(): Relations\HasMany
    {
        return $this->hasMany('App\Models\SupplierOrder');
    }

    public function alerts(): Relations\HasMany
    {
        return $this->hasMany('App\Models\Alert');
    }

    public function media(): Relations\MorphMany
    {
        return $this->morphMany('App\Models\Media', 'modelable');
    }

    public function scopedefault($query)
    {
        // TODO: Implémenter le scope
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
