<?php

namespace App\Models;

use App\Enums\ProductCategory;
use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $casts = [
        'productType' => ProductType::class,
        'category'=> ProductCategory::class,
    ];
    protected $fillable = [
        'productName',
        'productType',
        'productDesc',
        'productImgObj',
        'productTryOnQR',
        'category',
        'color',
        'size',
        'stock',
        'price',
        'deleted'
    ];

    public function scopeAvailable($query)
    {
        return $query->where('deleted', 0);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'productId');
    }
}
