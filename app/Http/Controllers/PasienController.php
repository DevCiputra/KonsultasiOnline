<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filterKeyword = $request->get('keyword');
        $pasien['pasien'] = Profile::with(['users'])->paginate(10);

        if($filterKeyword)
        {
            $pasien['pasien'] = Profile::with(['users'])
            ->whereHas('users', function($query) use ($filterKeyword) {
            $query->where('email', 'LIKE', "%$filterKeyword%");
            })
            ->paginate(10);
        }

        return view('pasien.index', $pasien);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,$id)
    {
        $profile = Profile::with('users')->findOrFail($id);

        // Ambil keyword pencarian
        $filterKeyword = $request->get('keyword');

        // Buat query dasar
        $query = Reservation::where('profile_id', $id)
                            ->with(['profilesPasiens.users', 'dokterProfiles.users']);

        // Jika ada keyword, filter berdasarkan reservation_code
        if($filterKeyword) {
            $query->where('reservation_code', 'like', '%' . $filterKeyword . '%');
        }

        // Eksekusi query
        $reservations = $query->get();

        return view('pasien.show', [
            'profile' => $profile,
            'reservations' => $reservations,
            'keyword' => $filterKeyword // Kirim keyword ke view untuk digunakan di form
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::all();
        $pasien = Profile::with(['users'])->findOrFail($id);
        return view('pasien.edit', compact('users', 'pasien'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pasien = Profile::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'gender' => 'sometimes|string|max:20',
            'golongan_darah' => 'sometimes|string|max:255',
            'riwayat_medis' => 'sometimes|string|max:255',
            'alergi' => 'sometimes|string|max:255',

        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input = $request->all();

        $pasien->update($input);
        return redirect()->route('pasien.index')->with('status', 'Data Pasien Berhasil di update');
    }

    public function updateReservation(Request $request, $id)
    {
       // Validasi input
        $validator = Validator::make($request->all(), [
            'tanggal_konsultasi' => 'required|string|max:255',
            'status_approve' => 'required|in:MENUNGGU,TERIMA,TOLAK',
            'link_pertemuan' => 'nullable|url',
            'catatan_konsultasi' => 'nullable|string',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cari reservasi berdasarkan ID
        $reservation = Reservation::findOrFail($id);

        // Update data reservasi
        $input = $request->all();
        $reservation->update($input);

        // Redirect kembali ke halaman detail dengan pesan sukses
        return redirect()->route('pasien.show', $reservation->profile_id)->with('status', 'Reservasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pasien = Profile::findOrFail($id);
        $pasien->delete();
        return redirect()->back()->with('status', 'Data Pasien Berhasil didelete');
    }
}
