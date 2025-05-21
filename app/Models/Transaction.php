<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Konstanta status transaksi
     */
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Konstanta tipe pembayaran
     */
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_CREDIT_CARD = 'credit_card';
    const PAYMENT_E_WALLET = 'e_wallet';

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
        'status_transaction',
        'payment_type',
        'order_id',
        'token_payment'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_transaction' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<int, string>
     */
    protected $dates = ['deleted_at'];

    /**
     * Boot method untuk mengatur nilai default
     */
    protected static function boot()
    {
        parent::boot();

        // Set nilai default untuk status transaksi
        static::creating(function ($transaction) {
            if (empty($transaction->status_transaction)) {
                $transaction->status_transaction = self::STATUS_PENDING;
            }
        });
    }

    /**
     * Accessor dan Mutator untuk memastikan status transaksi valid
     */
    public function getStatusTransactionAttribute($value)
    {
        $allowedStatuses = [
            self::STATUS_PENDING,
            self::STATUS_SUCCESS,
            self::STATUS_FAILED,
            self::STATUS_CANCELLED
        ];

        return in_array($value, $allowedStatuses) ? $value : self::STATUS_PENDING;
    }

    /**
     * Accessor dan Mutator untuk payment type
     */
    public function getPaymentTypeAttribute($value)
    {
        $allowedTypes = [
            self::PAYMENT_BANK_TRANSFER,
            self::PAYMENT_CREDIT_CARD,
            self::PAYMENT_E_WALLET
        ];

        return in_array($value, $allowedTypes) ? $value : null;
    }

    /**
     * Relasi dengan Profile pasien
     */
    public function profilesPasienTransactions()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    /**
     * Relasi dengan User pasien melalui profile
     */
    public function userPasienTransactions()
    {
        return $this->hasOneThrough(
            User::class,           // Target model
            Profile::class,        // Intermediate model
            'id',                  // Foreign key on profiles table
            'id',                  // Foreign key on users table
            'profile_id',          // Local key on transactions table
            'user_id'              // Local key on profiles table
        );
    }

    /**
     * Relasi dengan profil dokter
     */
    public function dokterProfileTransactions()
    {
        return $this->belongsTo(DoctorProfile::class, 'dokter_profiles', 'id');
    }

    /**
     * Relasi dengan user dokter melalui profil dokter
     * Metode ini tetap ada untuk kompatibilitas dengan controller
     */
    public function userDokterTransactions()
    {
        // Mencoba mendapatkan profil dokter dulu
        $doctorProfile = $this->dokterProfileTransactions;
        if ($doctorProfile) {
            return $doctorProfile->users();
        }
        return null;
    }

    /**
     * Relasi dengan reservasi
     */
    public function reservations()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    /**
     * Accessor untuk created_at dengan timezone Asia/Makassar
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Makassar')->format('Y-m-d H:i');
    }

    /**
     * Accessor untuk updated_at dengan timezone Asia/Makassar
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Makassar')->format('Y-m-d H:i');
    }

    /**
     * Method untuk memverifikasi pembayaran
     */
    public function verifyPayment()
    {
        // Logic untuk verifikasi pembayaran
        return true;
    }

    /**
     * Method untuk mendapatkan status dalam format yang lebih mudah dibaca
     */
    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_SUCCESS => 'Pembayaran Berhasil',
            self::STATUS_FAILED => 'Pembayaran Gagal',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];

        return $labels[$this->status_transaction] ?? 'Status Tidak Diketahui';
    }
}
