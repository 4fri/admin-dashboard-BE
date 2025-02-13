<?php

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $connection = 'itamDB';

    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = true;

    public function licenses()
    {
        return $this->hasMany(License::class, 'category_id');
    }
}
