<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = [
        'user_id',
        'related_id',
        'type',
        'title',
        'body',
        'image',
        'read_at',
    ];

    // create virtual attribute, can access it as a property directly, since the method name is getPathAttribute, so the attribute called path. Eg: $notification->path
    public function getPathAttribute()
    {
        switch ($this->type) {
            case 'order_status_update':
                return route('trackingOrder', $this->related_id);
            case 'comment_add':
            case 'admin_reply':
            case 'product_suggestion':
            case 'new_product':
                return route('product.detail', $this->related_id);
            case 'product_restock':
            case 'price_drop':
                return route('showCart');
            default:
                return url('/');
        }
    }
}
