<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Alert extends Model
{
    use HasFactory;

    protected $table = 'alerts';

    protected $fillable = [
        'product_id',
        'variant_id',
        'warehouse_id',
        'alert_type',
        'title',
        'message',
        'details',
        'current_quantity',
        'threshold_quantity',
        'is_read',
        'is_resolved',
        'read_at',
        'resolved_by',
        'resolved_at',
        'resolution_notes',
        'recommended_action',
        'recommended_quantity',
        'notification_channel',
        'notification_sent',
        'notification_sent_at'
    ];


    protected $casts = [
        'details' => 'array',
        'current_quantity' => 'decimal:3',
        'threshold_quantity' => 'decimal:3',
        'recommended_quantity' => 'decimal:3',
        'is_read' => 'boolean',
        'is_resolved' => 'boolean',
        'read_at' => 'datetime',
        'resolved_at' => 'datetime',
        'notification_sent' => 'boolean',
        'notification_sent_at' => 'datetime'
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

    public function resolver(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'resolver_id');
    }

    public const TYPE_LOW_STOCK = 'TYPE_LOW_STOCK';
    public const TYPE_OUT_OF_STOCK = 'TYPE_OUT_OF_STOCK';
    public const TYPE_OVERSTOCK = 'TYPE_OVERSTOCK';
    public const TYPE_EXPIRING_SOON = 'TYPE_EXPIRING_SOON';
    public const TYPE_EXPIRED = 'TYPE_EXPIRED';
    public const TYPE_PRICE_CHANGE = 'TYPE_PRICE_CHANGE';
    public const TYPE_SUPPLIER_DELAY = 'TYPE_SUPPLIER_DELAY';

    public function scopeunread($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopeunresolved($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopebyType($query)
    {
        // TODO: Implémenter le scope
    }

    public function markAsRead()
    {
        // TODO: Implémenter la méthode
    }

    public function markAsResolved()
    {
        // TODO: Implémenter la méthode
    }
}
