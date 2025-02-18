<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id')->orderBy('sort_order', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id')->orderBy('sort_order', 'asc');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id')->withDefault();
    }
}
