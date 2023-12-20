<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    protected $fillable = [
        'orderId',
        'userId',
        'transactionId',
        'paymentMethod',
        'paymentDate',
        'totalPaymentFee',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
