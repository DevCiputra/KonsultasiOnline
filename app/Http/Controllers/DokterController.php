<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryPolyclinic;
use App\Models\DoctorProfile;
use App\Models\JadwalPraktek;
use App\Models\PengalamanPraktek;
use App\Models\RiwayatPendidikan;
use App\Models\TindakanMedis;
use App\Models\User;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filterKeyword = $request->get('keyword');
        $filterStatusKonsultasi = $request->get('konsultasi');
        $filterStatusReservasi = $request->get('reservasi');

        $query = DoctorProfile::with(['users']);

        // Filter berdasarkan email
        if($filterKeyword) {
            $query->whereHas('users', function($q) use ($filterKeyword) {
                $q->where('email', 'LIKE', "%$filterKeyword%");
            });
        }

        // Filter berdasarkan status konsultasi
        if($filterStatusKonsultasi) {
            $query->where('konsultasi', $filterStatusKonsultasi);
        }

        // Filter berdasarkan status reservasi
        if($filterStatusReservasi) {
            $query->where('reservasi', $filterStatusReservasi);
        }

        $dokter['dokter'] = $query->paginate(10);

        // Kirim juga data status untuk dropdown
        $dokter['status_options'] = ['OPEN', 'CLOSE'];

        return view('dokter.index', $dokter);
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
    public function show($id)
    {
        $dokter = DoctorProfile::with('category_polyclinics', 'users', 'jadwals', 'pendidikans', 'pengalamans', 'medis')->findOrFail($id);
        return view('dokter.show', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoryPolyclinic = CategoryPolyclinic::all();
        $users = User::all();
        $dokter = DoctorProfile::with(['users'])->findOrFail($id);
        return view('dokter.edit', compact('users', 'dokter', 'categoryPolyclinic'));
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
        // Validasi input
        $this->validate($request, [
            'category_polyclinic_id' => 'required|exists:category_polyclinics,id',
            'spesialis_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'biografi' => 'nullable|string',
            'link_accuity' => 'required|url',
            'cv_dokter' => 'nullable|file|mimes:pdf|max:2048', // max 2MB
            'payment_konsultasi' => 'required|numeric|min:0',
            'payment_strike' => 'nullable|numeric|min:0',
            'konsultasi' => 'sometimes|in:OPEN,CLOSE',
            'reservasi' => 'sometimes|in:OPEN,CLOSE',
            'status_dokter' => 'sometimes|in:AKTIF,SIBUK'
        ]);

        // Cari data dokter berdasarkan ID
        $dokter = DoctorProfile::findOrFail($id);

        // Siapkan data untuk diupdate
        $input = $request->all();

        // Handle upload file cv_dokter
    if($request->hasFile('cv_dokter')) {
        if($request->file('cv_dokter')->isValid())
        {
            // Hapus file lama jika ada
            if(!empty($dokter->cv_dokter) && file_exists(env('UPLOAD_PATH') . '/' . $dokter->cv_dokter)) {
                unlink(env('UPLOAD_PATH') . '/' . $dokter->cv_dokter);
            }

            $cvDokter = $request->file('cv_dokter');
            $extensions = $cvDokter->getClientOriginalExtension();
            $cvDokterUpload = "cvDokter/".date('YmdHis').".".$extensions;
            $cvDokterPath = env('UPLOAD_PATH'). "/cvDokter";
            $request->file('cv_dokter')->move($cvDokterPath, date('YmdHis').".".$extensions);
            $input['cv_dokter'] = $cvDokterUpload;
        }
    } elseif ($request->has('remove_cv') && $request->remove_cv == 1) {
        // Hapus file jika checkbox remove_cv dicentang
        if(!empty($dokter->cv_dokter) && file_exists(env('UPLOAD_PATH') . '/' . $dokter->cv_dokter)) {
            unlink(env('UPLOAD_PATH') . '/' . $dokter->cv_dokter);
        }
        $input['cv_dokter'] = null;
    } else {
        // Jika tidak ada perubahan pada file, jangan update field ini
        unset($input['cv_dokter']);
    }

        // Update data dokter
        $dokter->update($input);

        return redirect()->route('dokter.index')
                        ->with('status', 'Data dokter berhasil diperbarui');
    }

    public function storeJadwal(Request $request, $id)
    {
        // Validate input
        $this->validate($request, [
        'hari' => 'required|string|',
        'jadwal_jam' => 'required|string|max:50',
        'dokter_profile_id' => 'required|exists:doctor_profiles,id'
        ]);
        // Create new jadwal
        $jadwal = new \App\Models\JadwalPraktek();
        $jadwal->dokter_profile_id = $request->dokter_profile_id;
        $jadwal->hari = $request->hari;
        $jadwal->jadwal_jam = $request->jadwal_jam;
        $jadwal->save();
        return redirect()->route('dokter.show', $id)
        ->with('status', 'Jadwal praktek berhasil ditambahkan');
    }

    public function pendidikanDokter(Request $request , $id)
    {
        // Validate input
        $this->validate($request, [
        'nama_riwayat_pendidikan' => 'required|string|',
        'dokter_profile_id' => 'required|exists:doctor_profiles,id'
        ]);
        // Create new jadwal
        $pendidikan = new \App\Models\RiwayatPendidikan();
        $pendidikan->dokter_profile_id = $request->dokter_profile_id;
        $pendidikan->nama_riwayat_pendidikan = $request->nama_riwayat_pendidikan;
        $pendidikan->save();
        return redirect()->route('dokter.show', $id)
        ->with('status', 'Pendidikan Dokter berhasil ditambahkan');
    }

    public function pengalamanDokter(Request $request , $id)
    {
        // Validate input
        $this->validate($request, [
        'nama_pengalaman_praktek' => 'required|string|',
        'dokter_profile_id' => 'required|exists:doctor_profiles,id'
        ]);
        // Create new jadwal
        $pengalaman = new \App\Models\PengalamanPraktek();
        $pengalaman->dokter_profile_id = $request->dokter_profile_id;
        $pengalaman->nama_pengalaman_praktek = $request->nama_pengalaman_praktek;
        $pengalaman->save();
        return redirect()->route('dokter.show', $id)
        ->with('status', 'Pengalaman Dokter berhasil ditambahkan');
    }

    public function tindakanMedis(Request $request , $id)
    {
        $this->validate($request, [
        'nama_tindakan_medis' => 'required|string|',
        'dokter_profile_id' => 'required|exists:doctor_profiles,id'
        ]);
        // Create new jadwal
        $tindakan = new \App\Models\TindakanMedis();
        $tindakan->dokter_profile_id = $request->dokter_profile_id;
        $tindakan->nama_tindakan_medis = $request->nama_tindakan_medis;
        $tindakan->save();
        return redirect()->route('dokter.show', $id)
        ->with('status', 'Tindakan Medis Dokter berhasil ditambahkan');
    }

    public function deleteJadwal(Request $request,$id)
    {
        $jadwal = JadwalPraktek::findOrFail($id);
        $dokter_id = $jadwal->dokter_profile_id; // Simpan ID dokter sebelum menghapus jadwal
        $jadwal->delete();

        return redirect()->route('dokter.show', $dokter_id)
            ->with('status', 'Jadwal Praktek Dokter Berhasil dihapus');
    }

    public function deletePendidikan($id)
    {
        $pendidikan = RiwayatPendidikan::findOrFail($id);
        $dokter_id = $pendidikan->dokter_profile_id;
        $pendidikan->delete();
        return redirect()->back()->with('status', 'Riwayat pendidikan Berhasil didelete');
        return redirect()->route('dokter.show', $dokter_id)
        ->with('status', 'Riwayat Pendidikan Berhasil di hapus');
    }

    public function deletePengalaman(Request $request,$id)
    {
        $pengalaman = PengalamanPraktek::findOrFail($id);
        $dokter_id = $pengalaman->dokter_profile_id;
        $pengalaman->delete();
        return redirect()->back()->with('status', 'Pengalaman Dokter Berhasil didelete');
        return redirect()->route('dokter.show', $dokter_id)
        ->with('status', 'Pengalaman Dokter Berhasil di hapus');
    }

    public function deleteMedis($id)
    {
        $medis = TindakanMedis::findOrFail($id);
        $dokter_id = $medis->dokter_profile_id;
        $medis->delete();
        return redirect()->back()->with('status', 'Tindakan Medis Dokter Berhasil didelete');
        return redirect()->route('dokter.show', $dokter_id)
        ->with('status', 'Tindakan Medis Dokter Berhasil didelete');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dokter = DoctorProfile::findOrFail($id);
        $dokter->delete();
        return redirect()->back()->with('status', 'Doctor Berhasil didelete');
    }
}
