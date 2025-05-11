<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function PostReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required|exists:profiles,id',
            'dokter_profile_id' => 'required|exists:doctor_profiles,id',
            'tanggal_konsultasi' => 'required|string|max:255',
            'link_pertemuan' => 'sometimes|nullable',
            'catatan_konsultasi' => 'sometimes|nullable',
            'keluhan_utama' => 'sometimes',
            'date_konsultasi_log' => 'sometimes|date',
            'status_approve' => 'sometimes|string|max:20',
            'reservation_code' => 'sometimes|string|max:255'
        ]);


        if($validator->fails()) {
            return ResponseFormmater::error(
                null,
                $validator->errors(),
                500
            );
        }

        $reservationCode = $request->reservation_code ?? $this->generateReservationCode($request->dokter_profile_id);

        $Reservation = Reservation::create([
            'profile_id' => $request->profile_id,
            'dokter_profile_id' => $request->dokter_profile_id,
            'tanggal_konsultasi' => $request->tanggal_konsultasi,
            'link_pertemuan' => $request->link_pertemuan,
            'catatan_konsultasi' => $request->catatan_konsultasi,
            'keluhan_utama' => $request->keluhan_utama,
            'date_konsultasi_log' => $request->date_konsultasi_log,
            'status_approve' => $request->status_approve,
            'reservation_code' => $reservationCode,
        ]);


        try {
            $Reservation->save();
            return ResponseFormmater::success(
                $Reservation,
                'Data Reservasi  Berhasil di tambahkan'
            );
        }

        catch(Exception $error) {
            return ResponseFormmater::error(
                $error->getMessage(),
                'Data Reservasi  tidak ada',
                404
            );
        }
    }

    private function generateReservationCode($doctorId)
    {
        $prefix = 'RES';
        $doctorPrefix = 'DR' . str_pad($doctorId, 3, '0', STR_PAD_LEFT);
        $datePart = Carbon::now()->format('YmdHis');
        $randomString = strtoupper(substr(uniqid(), -5));

        return "{$prefix}-{$doctorPrefix}-{$datePart}-{$randomString}";
    }


    public function FetchReservation (Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $status_approve = $request->input('status_approve');


        if($id)
        {
            $Reservation = Reservation::with(['profilesPasiens.users', 'dokterProfiles.users'])->find($id);

            if($Reservation)
            {
                return ResponseFormmater::success(
                    $Reservation,
                    'Reservation berhasil diambil'
                );
            }

            else {
                return ResponseFormmater::error(
                    null,
                    'Reservation tidak ditemukan',
                    404,
                );
            }
        }

        $Reservation = Reservation::with(['profilesPasiens.users', 'dokterProfiles.users']);

        if($status_approve)
        {
            $Reservation->where('status_approve', 'like', '%' . $status_approve . '%');
        }


        return ResponseFormmater::success(
            $Reservation->paginate($limit),
            'Reservation berhasil diambil'
        );
    }


    public function updateReservation (Request $request, $id)
    {
        $Reservation = Reservation::findOrFail($id);
        $data = $request->all();

        $Reservation->update($data);
        return ResponseFormmater::success(
            $Reservation,
            'Data Reservation Berhasil di update'
        );
    }
}
