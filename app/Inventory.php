<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'weight',
        'sale_price',
        'delivered_date',
    ];

    protected $casts = [
      'sale_price' => 'double',
      'weight' => 'double',
    ];

    public function product()
    {
      return $this->belongsTo('App\Product');
    }
}
