<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorProfileController extends Controller
{
    public function FetchDokterProfile(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $spesialis_name = $request->input('spesialis_name');
        $category_polyclinic_id = $request->input('category_polyclinic_id');
        $cheap = $request->input('cheap');
        $expensive = $request->input('expensive');
        $konsultasi = $request->input('konsultasi');
        $reservasi = $request->input('reservasi');
        $status_dokter = $request->input('status_dokter');
        $user_name = $request->input('user_name'); // Menambahkan parameter untuk nama user
        $hari = $request->input('hari'); // Menambahkan parameter untuk filter hari


        if($id)
        {
            $DokterProfile = DoctorProfile::with(['category_polyclinics', 'users', 'ulasans', 'jadwals', 'pendidikans', 'pengalamans', 'medis'])->find($id);

            if($DokterProfile)
            {
                return ResponseFormmater::success(
                    $DokterProfile,
                    'Dokter Profile berhasil diambil'
                );
            }

            else {
                return ResponseFormmater::error(
                    null,
                    'Dokter Profile tidak ditemukan',
                    404,
                );
            }
        }

        $DokterProfile = DoctorProfile::select([
            'id',
            'konsultasi',
            'link_accuity',
            'reservasi',
            'status_dokter',
            'spesialis_name',
            'category_polyclinic_id',
            'user_id'
            ])->with([
                'users:id,name,avatar,role',
                'category_polyclinics:id,category_polyclinic'
            ]);

        if($spesialis_name)
        {
            $DokterProfile->where('spesialis_name', 'like', '%' . $spesialis_name . '%');
        }

        if($category_polyclinic_id)
        {
            $DokterProfile->where('category_polyclinic_id', $category_polyclinic_id);
        }

        if($cheap) {
            $DokterProfile->orderBy('payment_konsultasi', 'asc');
        }

        if($expensive) {
            $DokterProfile->orderBy('payment_konsultasi', 'desc');
        }

        if($konsultasi)
        {
            $DokterProfile->where('konsultasi', 'like', '%' . $konsultasi . '%');
        }

        if($reservasi)
        {
            $DokterProfile->where('reservasi', 'like', '%' . $reservasi . '%');
        }

        if($status_dokter)
        {
            $DokterProfile->where('status_dokter', 'like', '%' . $status_dokter . '%');
        }

        // Menambahkan filter berdasarkan nama user jika parameter user_name diberikan
        if($user_name)
        {
            $DokterProfile->whereHas('users', function($query) use ($user_name) {
                $query->where('name', 'like', '%' . $user_name . '%');
            });
        }

        // Menambahkan filter berdasarkan hari di tabel jadwals
        if($hari)
        {
            // Jika hari=today, filter dokter yang memiliki jadwal hari ini
            if($hari == 'hari') {
                $hariIni = now()->format('l'); // Mengambil nama hari dalam bahasa Inggris (Monday, Tuesday, dll)
                $DokterProfile->whereHas('jadwals', function($query) use ($hariIni) {
                    $query->where('hari', $hariIni);
                });
            } else {
                // Jika hari spesifik diberikan, filter berdasarkan hari tersebut
                $DokterProfile->whereHas('jadwals', function($query) use ($hari) {
                    $query->where('hari', $hari);
                });
            }
        }


        return ResponseFormmater::success(
            $DokterProfile->paginate($limit),
            'Dokter Profile berhasil diambil'
        );
    }


    public function PostDokterProfile(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'category_polyclinic_id' => 'required|exists:category_polyclinics,id',
            'user_id' => 'required|exists:users,id',
            'spesialis_name' => 'required|string',
            'no_str' => 'sometimes|string',
            'biografi' => 'sometimes|string',
            'link_accuity' => 'sometimes|string',
            'cv_dokter' => 'sometimes|file|mimes:pdf|max:2048',
            'payment_konsultasi' => 'required|integer',
            'payment_strike' => 'sometimes|integer',
            'konsultasi' => 'sometimes',
            'reservasi' => 'sometimes',
            'status_dokter' => 'sometimes',
        ]);


        if($validator->fails()) {
            return ResponseFormmater::error(
                null,
                $validator->errors(),
                500
            );
        }

        $input = $request->all();

        if($request->file('cv_dokter')->isValid())
        {
            $cvDokter = $request->file('cv_dokter');
            $extensions = $cvDokter->getClientOriginalExtension();
            $cvDokterUpload = "cvDokter/".date('YmdHis').".".$extensions;
            $cvDokterPath = env('UPLOAD_PATH'). "/cvDokter";
            $request->file('cv_dokter')->move($cvDokterPath, $cvDokterUpload);
            $input['cv_dokter'] = $cvDokterUpload;
        }


        $DokterProfile = DoctorProfile::create($input);


        try {
            $DokterProfile->save();
            return ResponseFormmater::success(
                $DokterProfile,
                'Data Dokter Profile  Berhasil di tambahkan'
            );
        }

        catch(Exception $error) {
            return ResponseFormmater::error(
                $error->getMessage(),
                'Data Dokter profile  tidak ada',
                404
            );
        }
    }

    public function FetchDokterFavorite()
    {
        try {
            $doctors = DoctorProfile::with(['users', 'category_polyclinics'])
                ->withCount('ulasans')
                ->withAvg('ulasans', 'rating')
                ->orderBy('ulasans_count', 'desc')
                ->orderBy('ulasans_avg_rating', 'desc')
                ->limit(10)
                ->get();

            if ($doctors->isEmpty()) {
                return ResponseFormmater::error(
                    null,
                    'Data dokter favorit tidak ditemukan',
                    404
                );
            }

            $result = $doctors->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'category_polyclinic' => $doctor->category_polyclinics->category_polyclinic ?? '',
                    'doctor_name' => $doctor->users->name ?? '',
                    'avatar' => $doctor->users->avatar ?? '',
                    'spesialis' => $doctor->spesialis_name,
                    'payment_konsultasi' => (int)$doctor->payment_konsultasi,
                    'payment_strike' => (int)$doctor->payment_strike,
                    'ulasan_count' => $doctor->ulasans_count,
                    'average_rating' => round($doctor->ulasans_avg_rating, 1) ?? 0,
                    'status_dokter' => $doctor->status_dokter
                ];
            });

            return ResponseFormmater::success(
                $result,
                'Data dokter favorit berhasil diambil'
            );
        } catch (\Exception $e) {
            return ResponseFormmater::error(
                null,
                'Error: ' . $e->getMessage(),
                500
            );
        }
    }
}
