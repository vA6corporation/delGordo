<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'dispatched_date',
        'deliver_date',
        'delivered_date',
        'deleted_reason_id',
        'delivery_price',
        'delivery_id',
        'deliveryman_id',
        'payment_method_id',
        'payment_id',
        'on_model',
        'channel',
    ];

    protected $casts = [
      'delivery_price' => 'double',
    ];

    public function customer()
    {
      return $this->belongsTo('App\Customer');
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function delivery()
    {
      return $this->belongsTo('App\Delivery');
    }

    public function deliveryman()
    {
      return $this->belongsTo('App\User');
    }

    public function items()
    {
      return $this->hasMany('App\Inventory');
    }
}
