<?php

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $connection = 'itamDB';
    protected $table = 'licenses';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufactur::class, 'manufacturer_id');
    }

    public function seats()
    {
        return $this->hasMany(LicenseSeat::class, 'license_id');
    }
}
