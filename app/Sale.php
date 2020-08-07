<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'delivered_date',
        'deleted_id',
        'delivery_price',
        'delivery_id',
        'payment_method_id',
        'payment_id',
    ];

    protected $casts = [
      'delivery_price' => 'double',
    ];

    public function customer()
    {
      return $this->belongsTo('App\Customer');
    }

    public function delivery()
    {
      return $this->belongsTo('App\Delivery');
    }

    public function items()
    {
      return $this->hasMany('App\Inventory');
    }
}
