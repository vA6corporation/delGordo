<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'sale_price',
        'sub_category_id',
        'category_id',
        'image_url'
    ];

    protected $casts = [
      'sale_price' => 'double',
    ];

    protected $appends = [
      'weights',
      'packages'
    ];

    public function getWeightsAttribute()
    {
        return $this->inventory->sum('weight');
    }

    public function getPackagesAttribute()
    {
      return $this->inventory->count();
    }

    public function category()
    {
      return $this->belongsTo('App\Category');
    }

    public function subCategory()
    {
      return $this->belongsTo('App\SubCategory');
    }

    public function inventory() {
      return $this->hasMany('App\Inventory')->where('sale_id', NULL)->orderBy('weight', 'desc');
    }
}
