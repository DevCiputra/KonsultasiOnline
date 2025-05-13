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

        $DokterProfile = DoctorProfile::with(['category_polyclinics', 'users', 'ulasans', 'jadwals', 'pendidikans', 'pengalamans', 'medis']);

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
}
