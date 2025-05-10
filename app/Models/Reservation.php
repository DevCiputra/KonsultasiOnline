<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // INI PROFILE ID PASIEN
        'profile_id',
        // INI PROFILE ID DOKTER
        'dokter_profile_id',
        'tanggal_konsultasi',
        'waktu_konsultasi',
        'link_pertemuan',
        'catatan_konsultasi',
        'keluhan_utama',
        'date_konsultasi_log',
        // INI ADALAH STATUS APPROVE DARI DOKTER ('MENUNGGU', 'DITERIMA', 'DITOLAK')
        'status_approve',
        'reservation_code'
    ];

    protected $casts = [
        'date_konsultasi_log' => 'datetime',
    ];

    public function profilesPasiens()
    {
       return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    public function dokterProfiles()
    {
       return $this->belongsTo(DoctorProfile::class, 'dokter_profile_id', 'id');
    }

    public function userPasiens()
    {
        return $this->profilesPasiens->users();
    }

    public function userDokters()
    {
        return $this->dokterProfiles->users();
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
