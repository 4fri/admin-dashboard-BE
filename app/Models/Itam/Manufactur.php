<?php

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufactur extends Model
{
    use HasFactory;

    protected $connection = 'itamDB';

    protected $table = 'manufacturers';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = true;

    public function licenses()
    {
        return $this->hasMany(License::class, 'manufacturer_id');
    }
}
