<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
    ];

    public function customer()
    {
      return $this->belongsTo('App\Customer');
    }

    public function items()
    {
      return $this->hasMany('App\Inventory');
    }
}
