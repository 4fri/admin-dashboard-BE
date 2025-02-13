<?php

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $connection = 'itamDB';

    protected $table = 'assets';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = true;
}
