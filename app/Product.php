<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'description', 
        'sale_price',
        'sub_category_id',
        'category_id',
        'image_url',
        'unit_code',
    ];

    protected $casts = [
      'sale_price' => 'double',
    ];

    protected $appends = [
      'weights',
      'packages',
      'picked',
      'unit',
      'short_unit'
    ];

    public function getPickedAttribute()
    {
        return [];
    }

    public function getUnitAttribute()
    {
        switch ($this->unit_code) {
          case 'KGM':
            return 'Kilogramos';
          case 'BO':
            return 'Botellas';
          case 'BG':
            return 'Bolsas';
          case 'BX':
            return 'Cajas';
          default:
            return 'Unidades';
        }
    }

    public function getShortUnitAttribute()
    {
        switch ($this->unit_code) {
          case 'KGM':
            return 'Kg';
          case 'BG':
            return 'Bol';
          case 'BO':
            return 'Bot';
          case 'BX':
            return 'Caj';
          default:
            return 'Ud';
        }
    }

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

    public function inventoryAll() {
      return $this->hasMany('App\Inventory')->orderBy('weight', 'desc');
    }
}
