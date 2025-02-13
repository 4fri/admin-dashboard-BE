<?php

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseSeat extends Model
{
    use HasFactory;

    protected $connection = 'itamDB';
    protected $table = 'license_seats';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = true;


    public function licenses()
    {
        return $this->hasMany(License::class, 'company_id');
    }
}
