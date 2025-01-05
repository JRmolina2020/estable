<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'price_two',
        'cost',
        'priceSv',
        'price_twoSv',
        'type_iva',
        'stock',
        'categorie_id',
        'vm',
        'vd',
    ];
    public function facture_detail()
    {
        return $this->belongsTo('App\Models\FactureDetail');
    }
}
