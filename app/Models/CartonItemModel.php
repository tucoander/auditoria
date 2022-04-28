<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CartonItemModel extends Pivot
{
    public $table = 'carton_item';

    protected $fillable = [
        'carton_id', 
        'product_id', 
        'packed_quantity',
        'audit_quantity',
        'remaining_quantity',
        'exceed_quantity',
        'damaged_quantity',
        'items_status',
        'line',
        'audit_user'
    ];
}
