<?php

namespace App\Models;
use App\Enums\CartItemStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $table = 'cart_item';
    protected $casts = [
        'status' => CartItemStatus::class
    ];
    protected $fillable = [
        'productId',
        'userId',
        'color',
        'size',
        'quantity',
        'status',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }
}
