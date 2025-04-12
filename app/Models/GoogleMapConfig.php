<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleMapConfig extends Model
{
    protected $fillable = [
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];
}
