<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    protected $fillable = [
        'user_id',
        'dashboard',
        'pos',
        'posAdmin',
        'products',
        'categories',
        'subCategories',
        'inventories',
        'inventoriesAdmin',
        'customers',
        'sales',
        'salesAdmin',
        'users',
        'dispatchedsSale',
        'deliveriesSale',
        'deliveries',
        'paymentMethods',
        'deletedReasons',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'dashboard' => 'integer',
        'pos' => 'integer',
        'posAdmin' => 'integer',
        'products' => 'integer',
        'categories' => 'integer',
        'subCategories' => 'integer',
        'inventories' => 'integer',
        'inventoriesAdmin' => 'integer',
        'customers' => 'integer',
        'sales' => 'integer',
        'salesAdmin' => 'integer',
        'users' => 'integer',
        'dispatchedsSale' => 'integer',
        'deliveriesSale' => 'integer',
        'deliveries' => 'integer',
        'paymentMethods' => 'integer',
        'deletedReasons' => 'integer',
    ];
}
