{{-- doctor-profile.blade.php --}}
@extends('layouts.bootstrap')

@section('content')
<div class="container py-4">
    @include('alert.success')
    @include('alert.failed')
    <div class="card shadow border-0">

        <!-- Header with status badge -->
        <div class="position-relative">
            <div class="bg-primary" style="height: 150px;"></div>
            <div class="position-absolute top-0 end-0 m-3">
                @if($dokter->status_dokter == 'AKTIF')
                    <span class="badge bg-success d-flex align-items-center p-2">
                        <span class="bg-white rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                        AKTIF
                    </span>
                @else
                    <span class="badge bg-danger d-flex align-items-center p-2">
                        <span class="bg-white rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                        SIBUK
                    </span>
                @endif
            </div>
        </div>

        <!-- Doctor info card -->
        <div class="card-body position-relative px-4">
            <div class="row">
                <div class="col-md-3 text-center text-md-start">
                    <!-- Profile picture placeholder -->
                    <div class="position-relative mt-n5 mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center border border-4 border-white shadow" style="width: 130px; height: 130px; overflow: hidden;">
                            @if($dokter->users->avatar)
                                <img src="{{$dokter->users->avatar }}" alt="{{ $dokter->users->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <i class="bi bi-person-circle text-secondary" style="font-size: 80px;"></i>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="mt-md-3">
                        <h1 class="h3 fw-bold">{{ $dokter->users->name }}</h1>
                        <div class="d-flex align-items-center mt-2 text-primary">
                            <i class="bi bi-award me-2"></i>
                            <span>{{ $dokter->spesialis_name }}</span>
                        </div>
                        <div class="d-flex align-items-center mt-2 text-secondary">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>Policlinic {{ $dokter->category_polyclinics->category_polyclinic }}</span>
                        </div>
                        <div class="d-flex align-items-center mt-2 text-secondary">
                            <i class="bi bi-file-text me-2"></i>
                            <span>STR: {{ $dokter->gender }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Availability badges -->
            <div class="mt-4">
                @if($dokter->konsultasi)
                    <span class="badge bg-primary bg-opacity-10 text-primary me-2 p-2">
                        <i class="bi bi-clock me-1"></i>
                        Konsultasi Online
                    </span>
                @endif
                @if($dokter->reservasi)
                    <span class="badge bg-purple bg-opacity-10 text-purple me-2 p-2" style="color: #6f42c1; background-color: rgba(111, 66, 193, 0.1);">
                        <i class="bi bi-calendar-check me-1"></i>
                        Reservasi Tersedia
                    </span>
                @endif
            </div>

            <!-- Biography section -->
            <div class="mt-4">
                <h2 class="h5 fw-bold">Biografi</h2>
                <p class="mt-2 text-secondary">{{ $dokter->biografi }}</p>
            </div>

            <!-- Payment info -->
            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex align-items-center text-secondary">
                            <i class="bi bi-currency-dollar me-2 text-primary"></i>
                            <span class="fw-medium">Biaya Konsultasi</span>
                        </div>
                        <div class="mt-2 h5 fw-bold">
                            Rp {{ number_format($dokter->payment_konsultasi, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex align-items-center text-secondary">
                            <i class="bi bi-currency-dollar me-2 text-primary"></i>
                            <span class="fw-medium">Biaya Pembatalan</span>
                        </div>
                        <div class="mt-2 h5 fw-bold">
                            <del class="text-danger">Rp {{ number_format($dokter->payment_strike, 0, ',', '.') }}</del>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for adding doctor schedule -->
            <div class="modal fade" id="jadwalDokterModal{{ $dokter->id }}" tabindex="-1" role="dialog" aria-labelledby="jadwalDokterModalLabel{{ $dokter->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="jadwalDokterModalLabel">Tambah Jadwal Praktek</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('dokter.storeJadwal', $dokter->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="dokter_profile_id" value="{{ $dokter->id }}">

                                <div class="form-group mb-3">
                                    <label for="hari" class="form-label">Hari Praktek</label>
                                    <select class="form-control @error('hari') is-invalid @enderror" id="hari" name="hari" required>
                                        <option value="" selected disabled>Pilih Hari</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                    </select>
                                    @error('hari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="jadwal_jam" class="form-label">Jadwal Jam</label>
                                    <input type="text" class="form-control @error('jadwal_jam') is-invalid @enderror" id="jadwal_jam" name="jadwal_jam" placeholder="Contoh: 08:00 - 12:00" required>
                                    @error('jadwal_jam')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format: Jam Mulai - Jam Selesai (Contoh: 08:00 - 12:00)</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Pendidikan Dokter --}}

            <div class="modal fade" id="pendidikanDokter{{ $dokter->id }}" tabindex="-1" role="dialog" aria-labelledby="pendidikanModalDokter{{ $dokter->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pendidikanModalDokter">Tambah Pendidikan Dokter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('dokter.pendidikanDokter', $dokter->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="dokter_profile_id" value="{{ $dokter->id }}">

                                <div class="form-group mb-3">
                                    <label for="nama_riwayat_pendidikan" class="form-label">Riwayat Pendidikan</label>
                                    <input type="text" class="form-control @error('nama_riwayat_pendidikan') is-invalid @enderror" id="nama_riwayat_pendidikan" name="nama_riwayat_pendidikan" placeholder="Riwayat Pendidikan dokter" required>
                                    @error('nama_riwayat_pendidikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Tuliskan institusi, gelar, jurusan, dan tahun lulus untuk setiap jenjang pendidikan dokter</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            {{-- Pengalaman Dokter --}}
            <div class="modal fade" id="pengalamanDokter{{ $dokter->id }}" tabindex="-1" role="dialog" aria-labelledby="pengalamanModalDokter{{ $dokter->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pengalamanModalDokter">Tambah Pengalaman Dokter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('dokter.pengalamanDokter', $dokter->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="dokter_profile_id" value="{{ $dokter->id }}">

                                <div class="form-group mb-3">
                                    <label for="nama_pengalaman_praktek" class="form-label">Pengalaman Dokter</label>
                                    <input type="text" class="form-control @error('nama_pengalaman_praktek') is-invalid @enderror" id="nama_pengalaman_praktek" name="nama_pengalaman_praktek" placeholder="Pengalaman Praktek dokter" required>
                                    @error('nama_pengalaman_praktek')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Masukkan riwayat praktek dan pengalaman profesional, termasuk rumah sakit tempat praktek, posisi, dan durasi</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tindakan Medis Dokter --}}
            <div class="modal fade" id="tindakanMedis{{ $dokter->id }}" tabindex="-1" role="dialog" aria-labelledby="tindakanModalMedis{{ $dokter->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tindakanModalMedis">Tambah Tindakan Medis Dokter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('dokter.tindakanMedis', $dokter->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="dokter_profile_id" value="{{ $dokter->id }}">

                                <div class="form-group mb-3">
                                    <label for="nama_tindakan_medis" class="form-label">Tindakan Medis Dokter</label>
                                    <input type="text" class="form-control @error('nama_tindakan_medis') is-invalid @enderror" id="nama_tindakan_medis" name="nama_tindakan_medis" placeholder="Tindakan Medis dokter" required>
                                    @error('nama_tindakan_medis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Tuliskan tindakan/prosedur medis yang dapat dilakukan oleh dokter (pisahkan dengan koma jika lebih dari satu</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Additional info -->
            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex align-items-center text-secondary">
                            <i class="bi bi-link-45deg me-2 text-primary"></i>
                            <span class="fw-medium">Link Appointment</span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ $dokter->link_accuity }}" target="_blank" class="text-primary text-decoration-none text-truncate d-block">
                                {{ $dokter->link_accuity }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex align-items-center text-secondary">
                            <i class="bi bi-clipboard me-2 text-primary"></i>
                            <span class="fw-medium">CV Dokter</span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ $dokter->cv_dokter }}" class="text-primary text-decoration-none">
                                Unduh CV
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
    </div>
    <button type="button" class="btn btn-sm btn-primary mr-3 mt-2" data-toggle="modal" data-target="#jadwalDokterModal{{ $dokter->id }}">
                <i class="fas fa-edit"></i> + Jadwal Dokter
    </button>
    <button type="button" class="btn btn-sm btn-success mr-3 mt-2" data-toggle="modal" data-target="#pendidikanDokter{{ $dokter->id }}">
                <i class="fas fa-edit"></i> + Pendidikan Dokter
    </button>
    <button type="button" class="btn btn-sm btn-warning mr-3 mt-2" data-toggle="modal" data-target="#pengalamanDokter{{ $dokter->id }}">
                <i class="fas fa-edit"></i> + Pengalaman Dokter
    </button>
    <button type="button" class="btn btn-sm btn-secondary mt-2" data-toggle="modal" data-target="#tindakanMedis{{ $dokter->id }}">
                <i class="fas fa-edit"></i> + Tindakan Medis
    </button>

    <!-- Jadwal Praktek Section -->
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0">Jadwal Praktek</h2>
        </div>

        @if($dokter->jadwals->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Hari</th>
                            <th scope="col">Jam Praktek</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokter->jadwals as $index => $jadwal)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>
                                <span class="fw-medium">{{ $jadwal->hari }}</span>
                            </td>
                            <td>{{ $jadwal->jadwal_jam }}</td>
                            <td>
                                <form action="{{ route('dokter.deleteJadwal', $jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        <i class="bi bi-trash">Delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        @endif
    </div>

    {{-- Pendidikan Dokter section --}}
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0">Riwayat Pendidikan Dokter</h2>
        </div>

        @if($dokter->pendidikans->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Pendidikan Dokter</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokter->pendidikans as $index => $pendidikanss)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>
                                <span class="fw-medium">{{ $pendidikanss->nama_riwayat_pendidikan }}</span>
                            </td>
                            <td>
                                <form action="{{ route('dokter.deletePendidikan', $pendidikanss->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus Pendidikan ini?')">
                                        <i class="bi bi-trash">Delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        @endif
    </div>


    {{-- Pengalaman Kerja Dokter --}}
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0">Pengalaman Dokter</h2>
        </div>

        @if($dokter->pengalamans->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Pengalaman Praktek Dokter</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokter->pengalamans as $index => $pengalamanss)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>
                                <span class="fw-medium">{{ $pengalamanss->nama_pengalaman_praktek }}</span>
                            </td>
                            <td>
                                <form action="{{ route('dokter.deletePengalaman', $pengalamanss->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        <i class="bi bi-trash">Delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        @endif
    </div>


    {{-- Tindakan Medis Dokter --}}
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0">Tindakan Medis Dokter</h2>
        </div>

        @if($dokter->medis->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tindakan Medis Dokter</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokter->medis as $index => $medic)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>
                                <span class="fw-medium">{{ $medic->nama_tindakan_medis}}</span>
                            </td>
                            <td>
                                <form action="{{ route('dokter.deleteMedis', $medic->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        <i class="bi bi-trash">Delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        @endif
    </div>

@endsection

@push('styles')
<style>
    .text-purple {
        color: #2a2a2b;
    }
    .bg-purple {
        background-color: #6f42c1;
    }
    .flex-grow-1 {
        flex: 1;
    }
    .gap-2 {
        gap: 0.5rem;
    }
</style>
@endpush





