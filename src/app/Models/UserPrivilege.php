<?php

namespace Tugelsikile\UserLevel\app\Models;

use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    protected $table = "user_privileges";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'r' => 'boolean', 'c' => 'boolean', 'u' => 'boolean', 'd' => 'boolean'
    ];
}
