<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'document',
        'address',
        'mobile',
        'reference',
        'email',
    ];
}
