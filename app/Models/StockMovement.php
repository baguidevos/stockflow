<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $fillable = [
        'product_id',
        'variant_id',
        'warehouse_id',
        'movement_type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_price',
        'reference_number',
        'notes',
        'created_by',
        'occurred_at'
    ];


    protected $casts = [
        'quantity' => 'decimal:3',
        'quantity_before' => 'decimal:3',
        'quantity_after' => 'decimal:3',
        'unit_price' => 'decimal:4',
        'occurred_at' => 'datetime'
    ];

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function variant(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ProductVariant', 'variant_id');
    }

    public function warehouse(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }

    public function creator(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    public const MOVEMENT_PURCHASE = 'MOVEMENT_PURCHASE';
    public const MOVEMENT_SALE = 'MOVEMENT_SALE';
    public const MOVEMENT_ADJUSTMENT = 'MOVEMENT_ADJUSTMENT';
    public const MOVEMENT_TRANSFER_IN = 'MOVEMENT_TRANSFER_IN';
    public const MOVEMENT_TRANSFER_OUT = 'MOVEMENT_TRANSFER_OUT';
    public const MOVEMENT_RETURN = 'MOVEMENT_RETURN';
    public const MOVEMENT_RETURN_TO_SUPPLIER = 'MOVEMENT_RETURN_TO_SUPPLIER';
    public const MOVEMENT_DAMAGE = 'MOVEMENT_DAMAGE';
    public const MOVEMENT_INVENTORY = 'MOVEMENT_INVENTORY';

    public function scopeofType($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopebyProduct($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopebyDateRange($query)
    {
        // TODO: Implémenter le scope
    }
}
