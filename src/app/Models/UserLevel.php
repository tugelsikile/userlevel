<?php

namespace Tugelsikile\UserLevel\app\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    protected $table = "user_levels";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'is_super' => 'boolean', 'can_delete' => 'boolean'
    ];
}
