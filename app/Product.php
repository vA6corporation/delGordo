<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'sale_price',
        'subcategory_id',
        'category_id',
        'image_url'
    ];
}
