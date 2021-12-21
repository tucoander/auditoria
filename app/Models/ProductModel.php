<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Request;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','partnumber', 'description'];

    public function cartonsWithItem(){
        return $this->belongsToMany(CartonModel::class, 'carton_item', 'carton_id', 'product_id');
    }
}
