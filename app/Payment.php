<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'sale_id',
        'date_created',
    ];

    // protected $casts = [
    //   'sale_price' => 'double',
    //   'weight' => 'double',
    // ];

    // public function product()
    // {
    //   return $this->belongsTo('App\Product');
    // }

    // public function sale()
    // {
    //   return $this->belongsTo('App\Sale');
    // }
}
