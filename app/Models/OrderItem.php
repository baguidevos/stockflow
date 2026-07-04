<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_sku',
        'product_name',
        'ordered_quantity',
        'received_quantity',
        'returned_quantity',
        'unit_price',
        'tax_rate',
        'line_total',
        'notes'
    ];


    protected $casts = [
        'ordered_quantity' => 'decimal:3',
        'received_quantity' => 'decimal:3',
        'returned_quantity' => 'decimal:3',
        'unit_price' => 'decimal:4',
        'tax_rate' => 'decimal:2',
        'line_total' => 'decimal:4'
    ];

    public function order(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\SupplierOrder', 'order_id');
    }

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function getRemainingQuantityAttribute()
    {
        // TODO: Implémenter l'accessor
    }

    public function getReceivePercentageAttribute()
    {
        // TODO: Implémenter l'accessor
    }
}
