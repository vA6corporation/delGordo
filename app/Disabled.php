<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disabled extends Model
{
    protected $table = 'disabled';
    
    protected $fillable = [
        'office_id',
        'product_id',
    ];
}
