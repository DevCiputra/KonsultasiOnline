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
            'user_id' => 'required|exists:users,id',
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
            'user_id' => $request->user_id,
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
        $user_id = $request->input('user_id');


        if($id)
        {
            $ulasan = Ulasan::with(['userDokters'])->find($id);

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

        $ulasan = Ulasan::with(['userDokters']);

        if($user_id)
        {
            $ulasan->where('user_id', $user_id);
        }


        return ResponseFormmater::success(
            $ulasan->paginate($limit),
            'Ulasan berhasil diambil'
        );
    }
}
