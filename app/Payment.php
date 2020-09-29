<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'sale_id',
        'user_id',
        'mercadopago_id',
    ];

    // protected $casts = [
    //   'sale_price' => 'double',
    //   'weight' => 'double',
    // ];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    // public function sale()
    // {
    //   return $this->belongsTo('App\Sale');
    // }
}
