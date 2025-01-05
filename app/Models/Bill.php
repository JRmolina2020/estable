<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'user_id', 'name','price','date_bill'
    ];
    public function user()
{
    return $this->belongsTo('App\Models\User');
}

}
