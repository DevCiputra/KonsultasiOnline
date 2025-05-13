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
        'no_str',
        'biografi',
        'link_accuity',
        'cv_dokter',
        'payment_konsultasi',
        'payment_strike',
        'konsultasi',
        'reservasi',
        'status_dokter',
    ];

    public function category_polyclinics()
    {
        return $this->belongsTo(CategoryPolyclinic::class, 'category_polyclinic_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function jadwals()
    {
        return $this->hasMany(JadwalPraktek::class , 'dokter_profile_id', 'id');
    }

    public function pendidikans()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'dokter_profile_id', 'id');
    }

    public function pengalamans()
    {
        return $this->hasMany(PengalamanPraktek::class, 'dokter_profile_id', 'id');
    }

    public function medis()
    {
        return $this->hasMany(TindakanMedis::class, 'dokter_profile_id', 'id');
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'dokter_profile_id', 'id');
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('UTC')->setTimezone('Asia/Makassar')->format('Y-m-d H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('UTC')->setTimezone('Asia/Makassar')->format('Y-m-d H:i');
    }

    public function getCvDokterAttribute($value)
    {
        return env('ASSET_URL'). "/uploads/".$value;
    }
}
