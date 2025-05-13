<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function ProfileAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'gender' => 'sometimes',
            'golongan_darah' => 'sometimes',
            'riwayat_medis' => 'sometimes|string',
            'alergi' => 'sometimes|string',
            'tempat_tanggal_lahir' => 'sometimes|tempat_tanggal_lahir'
        ]);


        if($validator->fails()) {
            return ResponseFormmater::error(
                null,
                $validator->errors(),
                500
            );
        }

        $profile = Profile::create([
            'user_id' => $request->user_id,
            'gender' => $request->gender,
            'golongan_darah' => $request->golongan_darah,
            'riwayat_medis' => $request->riwayat_medis,
            'alergi' => $request->alergi,
            'tempat_tanggal_lahir' => $request->tempat_tanggal_lahir,
        ]);


        try {
            $profile->save();
            return ResponseFormmater::success(
                $profile,
                'Data Profile  Berhasil di tambahkan'
            );
        }

        catch(Exception $error) {
            return ResponseFormmater::error(
                $error->getMessage(),
                'Data profile  tidak ada',
                404
            );
        }
    }


    public function FetchProfile(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $riwayat_medis = $request->input('riwayat_medis');
        $user_id = $request->input('user_id');


        if($id)
        {
            $profile = Profile::with(['users'])->find($id);

            if($profile)
            {
                return ResponseFormmater::success(
                    $profile,
                    'Profile berhasil diambil'
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

        $profile = Profile::with(['users']);

        if($riwayat_medis)
        {
            $profile->where('riwayat_medis', 'like', '%' . $riwayat_medis . '%');
        }

        if($user_id)
        {
            $profile->where('user_id', $user_id);
        }


        return ResponseFormmater::success(
            $profile->paginate($limit),
            'Profile berhasil diambil'
        );
    }


    public function editProfile (Request $request , $id)
    {
        $profile = Profile::findOrFail($id);
        $data = $request->all();

        $profile->update($data);
        return ResponseFormmater::success(
            $profile,
            'Data Profile Berhasil di update'
        );
    }

    // Catatan lebih baik relasi user_id terus mengambil profile atau sebalik nya

    public function deleteProfile(Request $request , $id)
    {
        $profile = Profile::findOrFail($id);
        $data = $request->all();

        $profile->delete();
        return ResponseFormmater::success(
            $profile,
            'Data Profile Berhasil di delete'
        );
    }

}
