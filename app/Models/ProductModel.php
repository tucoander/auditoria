<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Request;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';

    public function cartons(){
        return $this->belongsToMany('App\Models\Carton');
    }
}
