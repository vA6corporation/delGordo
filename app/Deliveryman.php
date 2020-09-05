<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deliveryman extends Model
{
    protected $table = 'deliveryman';
    
    protected $fillable = [
        'name',
        'phone',
        'document',
    ];
}
