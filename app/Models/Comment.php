<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments'; 

    protected $fillable = [
        'user_id',
        'payment_id',
        'product_id',
        'rating',
        'review',
        'image',
        'likes',
        'admin_reply',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function likes(): Attribute {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }
}
