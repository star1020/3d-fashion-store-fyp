<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $table = 'delivery'; 

    protected $fillable = [
        'orderId',
        'deliveryManName',
        'deliveryManPhone',
        'deliveryCompany',
        'estimatedDeliveryDate',
        'actualDeliveryDate',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId');
    }
}
