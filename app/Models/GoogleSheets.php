<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSheets extends Model
{
    protected $fillable = [
        'google_account_id',
        'sheet_id',
        'sheet_list',
        'range',
    ];
}
