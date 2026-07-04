<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class SupplierOrder extends Model
{
    use HasFactory;

    protected $table = 'supplier_orders';

    protected $fillable = [
        'supplier_id',
        'warehouse_id',
        'order_number',
        'order_date',
        'expected_date',
        'received_date',
        'status',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'total_amount',
        'notes',
        'internal_notes',
        'created_by',
        'approved_by',
        'approved_at'
    ];


    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'received_date' => 'date',
        'subtotal' => 'decimal:4',
        'shipping_cost' => 'decimal:4',
        'tax_amount' => 'decimal:4',
        'total_amount' => 'decimal:4',
        'approved_at' => 'datetime'
    ];

    public function supplier(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }

    public function warehouse(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }

    public function creator(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    public function approver(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'approver_id');
    }

    public function items(): Relations\HasMany
    {
        return $this->hasMany('App\Models\OrderItem');
    }

    public function stockMovements(): Relations\HasMany
    {
        return $this->hasMany('App\Models\StockMovement');
    }

    public const STATUS_DRAFT = 'STATUS_DRAFT';
    public const STATUS_SENT = 'STATUS_SENT';
    public const STATUS_CONFIRMED = 'STATUS_CONFIRMED';
    public const STATUS_PARTIAL = 'STATUS_PARTIAL';
    public const STATUS_RECEIVED = 'STATUS_RECEIVED';
    public const STATUS_CANCELLED = 'STATUS_CANCELLED';

    public function scopebyStatus($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopebySupplier($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopepending($query)
    {
        // TODO: Implémenter le scope
    }

    public function calculateTotals()
    {
        // TODO: Implémenter la méthode
    }
}
