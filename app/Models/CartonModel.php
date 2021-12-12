<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartonModel extends Model
{
    use HasFactory;

    protected $table = 'cartons';
    protected $keyType = 'string';

    public function itemsPacked(){
        $this->belongsToMany('App\models\Product');
    }
}
