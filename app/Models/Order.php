<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    protected $fillable = [
        'cartItemIds',
        'userId',
        'deliveryAddress',
        'orderStatus',
        'orderDate',
    ];

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'orderId', 'id');
    }
}