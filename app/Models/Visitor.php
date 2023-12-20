<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';

    protected $fillable = [
        'session_id',
        'device',
        'browser',
        'os',
        'ip_address',
        'current_address',
        'page_view',
        'visit_time',
    ];
}
