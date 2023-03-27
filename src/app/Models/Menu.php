<?php

namespace Tugelsikile\UserLevel\app\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menus";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'is_function' => 'boolean', 'super' => 'boolean'
    ];
    public function parent() {
        return $this->belongsTo(Menu::class, 'parent', 'id');
    }
    public function childrenCollections() {
        return $this->hasMany(Menu::class,'id','parent')->orderBy('order', 'asc');
    }
}
