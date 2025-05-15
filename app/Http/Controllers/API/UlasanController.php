<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UlasanController extends Controller
{
    public function PostUlasan (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dokter_profile_id' => 'required|exists:doctor_profiles,id',
            'ulasan_pasien' => 'required|string',
            'nama_pasien' => 'required|string|max:255',
            'rating' => 'integer|sometimes',
        ]);


        if($validator->fails()) {
            return ResponseFormmater::error(
                null,
                $validator->errors(),
                500
            );
        }

        $rate = Ulasan::create([
            'dokter_profile_id' => $request->dokter_profile_id,
            'ulasan_pasien' => $request->ulasan_pasien,
            'nama_pasien' => $request->nama_pasien,
            'rating' => $request->rating,
        ]);


        try {
            $rate->save();
            return ResponseFormmater::success(
                $rate,
                'Data Ulasan  Berhasil di tambahkan'
            );
        }

        catch(Exception $error) {
            return ResponseFormmater::error(
                $error->getMessage(),
                'Data Rate  tidak ada',
                404
            );
        }
    }

    public function FetchUlasan(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $dokter_profile_id = $request->input('dokter_profile_id');


        if($id)
        {
            $ulasan = Ulasan::with(['profileDokters.users'])->find($id);

            if($ulasan)
            {
                return ResponseFormmater::success(
                    $ulasan,
                    'Ulasan berhasil diambil'
                );
            }

            else {
                return ResponseFormmater::error(
                    null,
                    'Profile tidak ditemukan',
                    404,
                );
            }
        }

        $ulasan = Ulasan::with(['profileDokters.users']);

        if($dokter_profile_id)
        {
            $ulasan->where('dokter_profile_id', $dokter_profile_id);
        }


        return ResponseFormmater::success(
            $ulasan->paginate($limit),
            'Ulasan berhasil diambil'
        );
    }
}
