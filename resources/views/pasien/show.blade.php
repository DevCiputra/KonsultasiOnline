@extends('layouts.bootstrap')

@section('title')
Detail Pasien - {{ $profile->users->name }}
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('pasien.index') }}" class="btn btn-secondary btn-sm mr-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <span class="h4 m-0">Detail Pasien: {{ $profile->users->name }}</span>
                </div>
            </div>

            <!-- Informasi Pasien -->
            <div class="card-body">
                <h5 class="border-bottom pb-2">
                    <i class="fas fa-user-circle text-primary mr-2"></i> Informasi Pasien
                </h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="150"><strong>Nama</strong></td>
                                <td>: {{ $profile->users->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: {{ $profile->users->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. WhatsApp</strong></td>
                                <td>: {{ $profile->users->whatsaap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: {{ $profile->users->address }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="150"><strong>Jenis Kelamin</strong></td>
                                <td>: {{ $profile->gender }}</td>
                            </tr>
                            <tr>
                                <td><strong>Golongan Darah</strong></td>
                                <td>: {{ $profile->golongan_darah }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Rekam Medis</strong></td>
                                <td>: <span class="badge badge-info">{{ $profile->riwayat_medis }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Alergi</strong></td>
                                <td>:
                                    @if ($profile->alergi)
                                        @foreach(explode(',', $profile->alergi) as $alergi)
                                            <span class="badge badge-danger mr-1">{{ trim($alergi) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Riwayat Reservasi -->
                <h5 class="border-bottom pb-2 mt-4">
                    <i class="fas fa-calendar-check text-primary mr-2"></i> Riwayat Reservasi
                </h5>

                @if(count($reservations) > 0)
                    <!-- Ringkasan Statistik Reservasi -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <div class="h1 text-primary">{{ count($reservations) }}</div>
                                            <div>Total Reservasi</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="h1 text-warning">
                                                {{ $reservations->where('status_approve', 'MENUNGGU')->count() }}
                                            </div>
                                            <div>Menunggu</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="h1 text-success">
                                                {{ $reservations->where('status_approve', 'TERIMA')->count() }}
                                            </div>
                                            <div>Diterima</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="h1 text-danger">
                                                {{ $reservations->where('status_approve', 'TOLAK')->count() }}
                                            </div>
                                            <div>Ditolak</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pencarian dan Tampilkan Semua -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('pasien.show', $profile->id) }}" class="mb-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="keyword" id="filterKeyword" class="form-control"
                                        placeholder="Cari kode reservasi..." value="{{ Request::get('keyword') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        @if(Request::get('keyword'))
                                            <a href="{{ route('pasien.show', $profile->id) }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i> Reset
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-6 text-center mb-4">
                            @if(Request::get('keyword'))
                                <div class="alert alert-info mt-2 mb-0 p-2">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        @if(count($reservations) > 0)
                                            Menampilkan {{ count($reservations) }} hasil untuk pencarian "{{ Request::get('keyword') }}"
                                        @else
                                            Tidak ditemukan reservasi dengan kode "{{ Request::get('keyword') }}"
                                        @endif
                                    </small>
                                </div>
                            @endif
                    </div>

                    <!-- Area Hasil Pencarian (Bagian baru) -->
                    <div id="searchResultArea" style="display: none;">
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0" id="searchResultTitle"><i class="fas fa-search mr-2"></i> Hasil Pencarian</h5>
                            </div>
                            <div class="card-body p-0">
                                <div id="searchResultContent"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tampilan Tabel Semua Reservasi -->
                    <div class="mb-4" id="allReservationsTable" style="display: none;">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Semua Reservasi Pasien</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="reservationsTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Reservasi</th>
                                                <th>Tanggal Konsultasi</th>
                                                <th>Dokter</th>
                                                <th>Status</th>
                                                <th>Keluhan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservations as $index => $reservation)
                                            <tr class="reservation-row" data-code="{{ $reservation->reservation_code }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td class="reservation_code">{{ $reservation->reservation_code }}</td>
                                                <td>{{ $reservation->tanggal_konsultasi }}</td>
                                                <td>{{ $reservation->dokterProfiles->users->name }} ({{ $reservation->dokterProfiles->spesialis_name }})</td>
                                                <td>
                                                    @if($reservation->status_approve == 'MENUNGGU')
                                                        <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> MENUNGGU</span>
                                                    @elseif($reservation->status_approve == 'TERIMA')
                                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> DITERIMA</span>
                                                    @elseif($reservation->status_approve == 'TOLAK')
                                                        <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> DITOLAK</span>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($reservation->keluhan_utama, 30) ?? 'Tidak ada' }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info show-accordion" data-target="#collapse{{ $index }}" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal{{ $reservation->id }}" title="Edit Reservasi">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion Detail Reservasi seperti sebelumnya -->
                    <div class="accordion" id="reservationAccordion">
                        @foreach($reservations as $index => $reservation)
                            <div class="card mb-3 reservation-card" data-code="{{ $reservation->reservation_code }}" data-index="{{ $index }}">
                                <div class="card-header d-flex justify-content-between align-items-center" id="heading{{ $index }}">
                                    <div>
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                                <i class="fas fa-calendar-day mr-2"></i> {{ $reservation->tanggal_konsultasi }}
                                            </button>
                                        </h5>
                                        <div>
                                            <small class="text-muted">Kode: <span class="reservation_code">{{ $reservation->reservation_code }}</span></small>
                                        </div>
                                    </div>
                                    <div>
                                        @if($reservation->status_approve == 'MENUNGGU')
                                            <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> MENUNGGU</span>
                                        @elseif($reservation->status_approve == 'TERIMA')
                                            <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> DITERIMA</span>
                                        @elseif($reservation->status_approve == 'TOLAK')
                                            <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> DITOLAK</span>
                                        @endif

                                    </div>
                                </div>

                                <div id="collapse{{ $index }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-parent="#reservationAccordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Detail Reservasi -->
                                            <div class="col-md-6">
                                                <h6 class="text-primary"><i class="fas fa-info-circle mr-1"></i> Detail Reservasi</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td width="170"><strong>Tanggal Konsultasi</strong></td>
                                                        <td>{{ $reservation->tanggal_konsultasi }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Status</strong></td>
                                                        <td>
                                                            @if($reservation->status_approve == 'MENUNGGU')
                                                                <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> MENUNGGU</span>
                                                            @elseif($reservation->status_approve == 'TERIMA')
                                                                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> DITERIMA</span>
                                                            @elseif($reservation->status_approve == 'TOLAK')
                                                                <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> DITOLAK</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Keluhan Utama</strong></td>
                                                        <td>{{ $reservation->keluhan_utama ?? 'Tidak ada' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Link Pertemuan</strong></td>
                                                        <td>
                                                            @if($reservation->link_pertemuan)
                                                                <a href="{{ $reservation->link_pertemuan }}" target="_blank" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-video mr-1"></i> Buka Link
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Belum tersedia</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Catatan Konsultasi</strong></td>
                                                        <td>{{ $reservation->catatan_konsultasi ?? 'Belum ada catatan' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Dibuat pada</strong></td>
                                                        <td>{{ $reservation->created_at }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Diperbarui pada</strong></td>
                                                        <td>{{ $reservation->updated_at }}</td>
                                                    </tr>
                                                </table>

                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal{{ $reservation->id }}">
                                                    <i class="fas fa-edit"></i> Edit Reservasi
                                                </button>
                                            </div>

                                            <!-- Info Dokter -->
                                            <div class="col-md-6">
                                                <h6 class="text-primary"><i class="fas fa-user-md mr-1"></i> Informasi Dokter</h6>
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="mr-3">
                                                                @if($reservation->dokterProfiles->users->avatar)
                                                                    <img src="{{ $reservation->dokterProfiles->users->avatar }}" alt="Dokter" class="img-circle" width="80">
                                                                @else
                                                                    <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                                                                        <i class="fas fa-user-md fa-2x"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-1">{{ $reservation->dokterProfiles->users->name }}</h5>
                                                                <p class="mb-0">
                                                                    <span class="badge badge-primary">
                                                                        <i class="fas fa-stethoscope mr-1"></i>
                                                                        {{ $reservation->dokterProfiles->spesialis_name }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <table class="table table-sm">
                                                            <tr>
                                                                <td width="150"><strong>No. STR</strong></td>
                                                                <td>{{ $reservation->dokterProfiles->no_str }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Email</strong></td>
                                                                <td>{{ $reservation->dokterProfiles->users->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>WhatsApp</strong></td>
                                                                <td>
                                                                    @php
                                                                        // Bersihkan nomor dari karakter non-digit
                                                                        $whatsappNumber = preg_replace('/[^0-9]/', '', $reservation->dokterProfiles->users->whatsaap);

                                                                        // Jika diawali dengan 0, ganti dengan 62
                                                                        if (substr($whatsappNumber, 0, 1) === '0') {
                                                                            $whatsappNumber = '62' . substr($whatsappNumber, 1);
                                                                        }
                                                                        // Jika belum diawali dengan 62, tambahkan 62
                                                                        elseif (substr($whatsappNumber, 0, 2) !== '62') {
                                                                            $whatsappNumber = '62' . $whatsappNumber;
                                                                        }
                                                                    @endphp
                                                                    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Halo Dok ' . $reservation->dokterProfiles->users->name . ', saya ' . $profile->users->name . ' pasien dengan kode reservasi ' . $reservation->reservation_code) }}" target="_blank" class="btn btn-sm btn-success">
                                                                        <i class="fab fa-whatsapp mr-1"></i> Chat Dokter
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Biaya Konsultasi</strong></td>
                                                                <td>
                                                                    <span class="text-primary font-weight-bold">Rp {{ number_format($reservation->dokterProfiles->payment_konsultasi, 0, ',', '.') }}</span>
                                                                    @if($reservation->dokterProfiles->payment_strike > 0)
                                                                        <br>
                                                                        <small class="text-muted"><del>Rp {{ number_format($reservation->dokterProfiles->payment_strike, 0, ',', '.') }}</del></small>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Status Dokter</strong></td>
                                                                <td>
                                                                    @if($reservation->dokterProfiles->status_dokter == 'AKTIF')
                                                                        <span class="badge badge-success">AKTIF</span>
                                                                    @else
                                                                        <span class="badge badge-warning">SIBUK</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Biografi</strong></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#biografiModal{{ $index }}">
                                                                        <i class="fas fa-book-reader mr-1"></i> Lihat Biografi
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Biografi Dokter -->
                            <div class="modal fade" id="biografiModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="biografiModalLabel{{ $index }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="biografiModalLabel{{ $index }}">
                                                <i class="fas fa-user-md mr-2"></i> Biografi {{ $reservation->dokterProfiles->users->name }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{ $reservation->dokterProfiles->biografi ?? 'Tidak ada biografi' }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit Reservasi (Tambahkan di sini) -->
                            <div class="modal fade" id="editModal{{ $reservation->id }}" tabindex="-1" role="dialog" aria-labelledby="editReservationLabel{{ $reservation->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('pasien.updateReservation', $reservation->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="editReservationLabel{{ $reservation->id }}">
                                                    <i class="fas fa-edit mr-2"></i> Edit Reservasi
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="tanggal_konsultasi">Tanggal Konsultasi</label>
                                                    <input type="text" name="tanggal_konsultasi" id="tanggal_konsultasi{{ $reservation->id }}" class="form-control @error('tanggal_konsultasi') is-invalid @enderror" value="{{ $reservation->tanggal_konsultasi }}" required>
                                                    @error('tanggal_konsultasi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Contoh format: Senin, 15 Mei 2025</small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="status_approve">Status Persetujuan</label>
                                                    <select name="status_approve" id="status_approve{{ $reservation->id }}" class="form-control @error('status_approve') is-invalid @enderror" required>
                                                        <option value="MENUNGGU" {{ $reservation->status_approve == 'MENUNGGU' ? 'selected' : '' }}>MENUNGGU</option>
                                                        <option value="TERIMA" {{ $reservation->status_approve == 'TERIMA' ? 'selected' : '' }}>TERIMA</option>
                                                        <option value="TOLAK" {{ $reservation->status_approve == 'TOLAK' ? 'selected' : '' }}>TOLAK</option>
                                                    </select>
                                                    @error('status_approve')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group" id="linkPertemuanGroup{{ $reservation->id }}" style="{{ $reservation->status_approve == 'TERIMA' ? '' : 'display: none;' }}">
                                                    <label for="link_pertemuan">Link Pertemuan</label>
                                                    <input type="url" name="link_pertemuan" id="link_pertemuan{{ $reservation->id }}" class="form-control @error('link_pertemuan') is-invalid @enderror" value="{{ $reservation->link_pertemuan }}" placeholder="https://meet.google.com/xxx-xxxx-xxx">
                                                    @error('link_pertemuan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Masukkan link pertemuan jika status TERIMA</small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="catatan_konsultasi">Catatan Konsultasi</label>
                                                    <textarea name="catatan_konsultasi" id="catatan_konsultasi{{ $reservation->id }}" class="form-control @error('catatan_konsultasi') is-invalid @enderror" rows="3">{{ $reservation->catatan_konsultasi }}</textarea>
                                                    @error('catatan_konsultasi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle mr-2"></i> Pasien belum memiliki riwayat reservasi.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-sm td {
        padding: 0.5rem;
    }
    .accordion .card-header {
        background-color: #f8f9fa;
    }
    .accordion .btn-link {
        color: #007bff;
        text-decoration: none;
    }
    .accordion .btn-link:hover {
        text-decoration: none;
    }
    .highlight {
        background-color: #ffffd3;
        font-weight: bold;
        padding: 0 2px;
        border-radius: 2px;
    }
    .search-result-card {
        margin-bottom: 1rem;
        border-left: 4px solid #007bff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .search-result-header {
        background-color: #e9f5ff;
        border-bottom: 1px solid #cce5ff;
        padding: 0.5rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .search-result-body {
        padding: 1rem;
    }
    .result-code {
        font-size: 1.1rem;
        font-weight: bold;
        color: #007bff;
    }
    .back-to-results {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: none;
    }
</style>
@endpush
