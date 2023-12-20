<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardClaim extends Model
{
    use HasFactory;
    protected $table = 'reward_claims';

    protected $fillable = [
        'user_id',
        'reward_id',
        'current_address',
        'delivery_address',
        'status',
    ];

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
