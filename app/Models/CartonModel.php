<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartonModel extends Model
{
    use HasFactory;

    protected $table = 'cartons';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','shipping_hu', 'document'];

    public function itemsPacked()
    {
        return $this->belongsToMany(ProductModel::class, 'carton_item', 'carton_id', 'product_id')
        ->withPivot(
            [
                'packed_quantity',
                'audit_quantity',
                'remaining_quantity',
                'exceed_quantity',
                'damaged_quantity',
                'items_status'
            ]
        )
        ;
    }

    public function cartonAlreadyContainItem($product){
        return $this->belongsToMany(ProductModel::class)
                ->wherePivot('product_id', $product);
    }
}
