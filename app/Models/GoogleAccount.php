<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleAccount extends Model
{
    protected $fillable = [
        'credentials',
        'access_token',
    ];

    protected $casts = [
        'credentials' => 'array',
        'access_token' => 'array',
    ];
}
