<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureDetail extends Model
{

    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'facture_id','product_id','quantity','price','sub','disc','tot'
    ];
    public function facture()
    {
        return $this->belongsTo('App\Models\Facture');
    }
    function products()
    {
        return $this->hasMany('App\Model\Products');
    }
}
