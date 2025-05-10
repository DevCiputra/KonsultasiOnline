<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_polyclinic_id',
        'user_id',
        'spesialis_name',
        'latar_pendidikan',
        'no_lisensi',
        'pengalaman',
        'biografi',
        'link_accuity',
        'jadwal_praktek',
        'cv_dokter',
        'payment_konsultasi',
    ];

    public function category_polyclinics()
    {
        return $this->belongsTo(CategoryPolyclinic::class, 'category_polyclinic_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('UTC')->setTimezone('Asia/Makassar')->format('Y-m-d H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('UTC')->setTimezone('Asia/Makassar')->format('Y-m-d H:i');
    }
}
