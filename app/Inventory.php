<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sale_id',
        'product_id',
        'weight',
        'sale_price',
        'dispatched_date',
        'delivered_date',
    ];

    protected $attributes = [
      'office_id' => 1,
    ];

    protected $casts = [
      'sale_price' => 'double',
      'weight' => 'double',
    ];

    public function product()
    {
      return $this->belongsTo('App\Product');
    }

    public function sale()
    {
      return $this->belongsTo('App\Sale');
    }
}
