<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_id',
        'dokter_profiles',
        'reservation_id',
        'total_transaction',
        // pending , success, cancelled
        'status_transaction',
        'payment_type',
        'order_id',
        'token_payment'

    ];

    public function profilesPasienTransactions()
    {
       return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    public function userPasienTransactions()
    {
       return $this->profilesPasienTransactions->users();
    }

    public function dokterProfileTransactions()
    {
       return $this->belongsTo(DoctorProfile::class, 'dokter_profiles', 'id');
    }

    public function userDokterTransactions()
    {
       return $this->dokterProfileTransactions->users();
    }

    public function reservations()
    {
       return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
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
