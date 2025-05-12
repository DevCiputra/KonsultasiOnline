@extends('layouts.bootstrap')

@section('title')
Edit Data Dokter
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('dokter.index')}}">Back</a>
            <h3>Edit {{$dokter->users->name}}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('dokter.update', [$dokter->id]) }}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="card-body">
                  <div class="form-group">
                    <label for="spesialis_name">Nama Spesialis</label>
                    <input type="text" class="form-control {{$errors->first('spesialis_name') ? 'is-invalid' : ''}}" name="spesialis_name" id="spesialis_name" placeholder="Enter Nama Spesialis Dokter" value="{{ $dokter->spesialis_name }}">
                    <span class="error invalid-feedback">{{$errors->first('spesialis_name')}}</span>
                  </div>


                  <div class="form-group">
                    <label for="no_str">Nomor STR Dokter</label>
                    <input type="number" class="form-control {{$errors->first('no_str') ? 'is-invalid' : ''}}" name="no_str" id="no_str" placeholder="Enter Nomor Str" value="{{ $dokter->no_str }}">
                    <span class="error invalid-feedback">{{$errors->first('no_str')}}</span>
                  </div>


                  <div class="form-group">
                    <label for="biografi">Biografi Dokter ( Opsional )</label>
                    <textarea class="form-control {{$errors->first('biografi') ? 'is-invalid' : ''}}"
                            name="biografi"
                            id="biografi"
                            rows="5"
                            placeholder="Masukkan biografi dokter (pendidikan, pengalaman kerja, keahlian khusus, dll)">{{ $dokter->biografi }}</textarea>
                    <span class="error invalid-feedback">{{$errors->first('biografi')}}</span>
                    <small class="text-muted">Tuliskan informasi mengenai latar belakang pendidikan, pengalaman profesional, spesialisasi, dan prestasi dokter.</small>
                 </div>

                 <div class="form-group">
                    <label for="link_accuity">Link Appointment (Acuity)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                        </div>
                        <input type="url"
                            class="form-control {{$errors->first('link_accuity') ? 'is-invalid' : ''}}"
                            name="link_accuity"
                            id="link_accuity"
                            placeholder="https://example.acuityscheduling.com/schedule.php?..."
                            value="{{ $dokter->link_accuity }}"
                            pattern="https?://.+"
                            title="Masukkan URL yang valid diawali dengan http:// atau https://">
                        <span class="error invalid-feedback">{{$errors->first('link_accuity')}}</span>
                    </div>
                    <small class="text-muted">Masukkan URL lengkap termasuk https:// untuk link appointment Acuity. Field ini wajib diisi dengan URL yang valid.</small>
                </div>

                <div class="form-group">
                    <label for="cv_dokter">CV Dokter (PDF)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-file-pdf"></i></span>
                        </div>
                        <div class="custom-file">
                            <input type="file"
                                class="custom-file-input {{$errors->first('cv_dokter') ? 'is-invalid' : ''}}"
                                id="cv_dokter"
                                name="cv_dokter"
                                accept=".pdf">
                            <label class="custom-file-label" for="cv_dokter">
                                {{ $dokter->cv_dokter ? basename($dokter->cv_dokter) : 'Pilih file PDF' }}
                            </label>
                        </div>
                        @if($dokter->cv_dokter)
                            <div class="input-group-append">
                                <a href="{{$dokter->cv_dokter }}" target="_blank" class="btn btn-outline-secondary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($errors->first('cv_dokter'))
                        <span class="text-danger">{{$errors->first('cv_dokter')}}</span>
                    @endif
                    <small class="text-muted">Unggah file CV dalam format PDF. Ukuran maksimal 2MB.</small>

                    @if($dokter->cv_dokter)
                        <div class="mt-2 d-flex align-items-center">
                            <span class="text-success mr-2"><i class="fas fa-check-circle"></i> CV sudah diunggah</span>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remove_cv" name="remove_cv" value="1">
                                <label class="custom-control-label text-danger" for="remove_cv">Hapus CV</label>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="payment_konsultasi">Biaya Konsultasi</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number"
                            class="form-control {{$errors->first('payment_konsultasi') ? 'is-invalid' : ''}}"
                            name="payment_konsultasi"
                            id="payment_konsultasi"
                            placeholder="Masukkan biaya konsultasi"
                            value="{{ $dokter->payment_konsultasi }}"
                            min="0"
                            step="1000">
                        <div class="input-group-append">
                            <span class="input-group-text">,00</span>
                        </div>
                    </div>
                    @if($errors->first('payment_konsultasi'))
                        <span class="text-danger">{{$errors->first('payment_konsultasi')}}</span>
                    @endif
                    <small class="text-muted">Masukkan biaya konsultasi dalam Rupiah (tanpa titik atau koma).</small>
                </div>

                <div class="form-group">
                    <label for="payment_strike">Biaya Konsultasi Coret</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number"
                            class="form-control {{$errors->first('payment_strike') ? 'is-invalid' : ''}}"
                            name="payment_strike"
                            id="payment_strike"
                            placeholder="Masukkan biaya konsultasi Coret"
                            value="{{ $dokter->payment_strike }}"
                            min="0"
                            step="1000">
                        <div class="input-group-append">
                            <span class="input-group-text">,00</span>
                        </div>
                    </div>
                    @if($errors->first('payment_strike'))
                        <span class="text-danger">{{$errors->first('payment_strike')}}</span>
                    @endif
                    <small class="text-muted">Masukkan biaya konsultasi dalam Rupiah (tanpa titik atau koma).</small>
                </div>

                <div class="form-group">
                    <label>Status Konsultasi</label>
                    <div class="d-flex mt-2">
                        <div class="custom-control custom-radio custom-control-success mr-4">
                            <input type="radio" id="konsultasi_open" name="konsultasi" class="custom-control-input" value="OPEN" {{ $dokter->konsultasi == 'OPEN' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="konsultasi_open">
                                <span class="badge badge-success"><i class="fas fa-door-open mr-1"></i> OPEN</span>
                                <span class="ml-2">Dokter tersedia untuk konsultasi</span>
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-danger">
                            <input type="radio" id="konsultasi_close" name="konsultasi" class="custom-control-input" value="CLOSE" {{ $dokter->konsultasi == 'CLOSE' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="konsultasi_close">
                                <span class="badge badge-secondary"><i class="fas fa-door-closed mr-1"></i> CLOSE</span>
                                <span class="ml-2">Dokter tidak tersedia untuk konsultasi</span>
                            </label>
                        </div>
                    </div>
                    @if($errors->first('konsultasi'))
                        <span class="text-danger">{{$errors->first('konsultasi')}}</span>
                    @endif
                    <small class="text-muted">Status ini menentukan apakah dokter dapat menerima konsultasi baru dari pasien.</small>
                </div>

                <div class="form-group">
                    <label>Status Reservasi</label>
                    <div class="d-flex mt-2">
                        <div class="custom-control custom-radio custom-control-success mr-4">
                            <input type="radio" id="reservasi_open" name="reservasi" class="custom-control-input" value="OPEN" {{ $dokter->reservasi == 'OPEN' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="reservasi_open">
                                <span class="badge badge-success"><i class="fas fa-door-open mr-1"></i> OPEN</span>
                                <span class="ml-2">Dokter tersedia untuk Reservasi</span>
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-danger">
                            <input type="radio" id="reservasi_close" name="reservasi" class="custom-control-input" value="CLOSE" {{ $dokter->reservasi == 'CLOSE' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="reservasi_close">
                                <span class="badge badge-secondary"><i class="fas fa-door-closed mr-1"></i> CLOSE</span>
                                <span class="ml-2">Dokter tidak tersedia untuk Reservasi</span>
                            </label>
                        </div>
                    </div>
                    @if($errors->first('reservasi'))
                        <span class="text-danger">{{$errors->first('reservasi')}}</span>
                    @endif
                    <small class="text-muted">Status ini menentukan apakah dokter dapat menerima Reservasi baru dari pasien.</small>
                </div>

                <div class="form-group">
                    <label>Status Ketersediaan Dokter</label>
                    <div class="d-flex mt-2">
                        <div class="custom-control custom-radio custom-control-success mr-4">
                            <input type="radio" id="status_dokter_aktif" name="status_dokter" class="custom-control-input" value="AKTIF" {{ $dokter->status_dokter == 'AKTIF' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="status_dokter_aktif">
                                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> AKTIF</span>
                                <span class="ml-2">Dokter tersedia dan siap melayani</span>
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-warning">
                            <input type="radio" id="status_dokter_sibuk" name="status_dokter" class="custom-control-input" value="SIBUK" {{ $dokter->status_dokter == 'SIBUK' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="status_dokter_sibuk">
                                <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> SIBUK</span>
                                <span class="ml-2">Dokter sedang sibuk atau tidak tersedia</span>
                            </label>
                        </div>
                    </div>
                    @if($errors->first('status_dokter'))
                        <span class="text-danger">{{$errors->first('status_dokter')}}</span>
                    @endif
                    <small class="text-muted">Status ini menunjukkan ketersediaan dokter saat ini untuk layanan di platform.</small>
                </div>

                  <div class="form-group">
                        <label for="category_polyclinic_id">Category Polyclinic</label>
                        <select name="category_polyclinic_id" id="category_polyclinic_id" class="form-control {{$errors->first('category_polyclinic_id') ?  'is-invalid' : ''}}">
                            @foreach($categoryPolyclinic as $categoryPolyclinics)
                            <option value="{{ $categoryPolyclinics->id }}" @if ($dokter->category_polyclinic_id == $categoryPolyclinics->id) selected @endif>{{ $categoryPolyclinics->category_polyclinic }}</option>
                            @endforeach
                        </select>
                        <span class="error invalid-feedback">{{$errors->first('category_polyclinic_id')}}</span>
                  </div>

                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">Update Data Dokter</button>
                </div>
              </form>
        </div>
      </div>
    </div>
  </div>
@endsection
