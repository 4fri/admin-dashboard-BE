<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasUuids;

    protected $table = 'routes';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];
}
