<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class VariantAttributeValue extends Model
{
    use HasFactory;

    protected $table = 'variant_attribute_values';

    protected $fillable = [
        'variant_id',
        'attribute_id',
        'attribute_value_id'
    ];


    public function variant(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ProductVariant', 'variant_id');
    }

    public function attribute(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Attribute', 'attribute_id');
    }

    public function attributeValue(): Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\AttributeValue', 'attribute_value_id');
    }


}
