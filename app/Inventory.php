<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'weight',
        'sale_price'
    ];

    protected $casts = [
      'sale_price' => 'double',
    ];

    public function product()
    {
      return $this->belongsTo('App\Product');
    }
}
