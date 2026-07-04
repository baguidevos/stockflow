<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent'
    ];


    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public function user(): Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function scopebyEntity($query)
    {
        // TODO: Implémenter le scope
    }

    public function scopebyAction($query)
    {
        // TODO: Implémenter le scope
    }

    public function logActivity()
    {
        // TODO: Implémenter la méthode
    }
}
