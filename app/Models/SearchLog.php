<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'query',
        'ip_address',
    ];
}
