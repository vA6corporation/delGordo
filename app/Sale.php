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
