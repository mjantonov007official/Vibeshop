<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    protected $fillable = [
        'business_type',
        'result',
        'ip_address',
        'user_agent',
    ];
}
