@extends('layouts.bootstrap')

@section('title')
Edit Data Pasien
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('pasien.index')}}">Back</a>
            <h3>Edit {{$pasien->users->name}}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pasien.update', [$pasien->id]) }}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="card-body">
                  <div class="form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control {{$errors->first('gender') ?  'is-invalid' : ''}}">
                        <option value="Laki-laki" @if ($pasien->gender == 'Laki-laki') selected @endif>Laki-laki</option>
                        <option value="Perempuan" @if ($pasien->gender == 'Perempuan') selected @endif>Perempuan</option>
                    </select>
                    <span class="error invalid-feedback">{{$errors->first('gender')}}</span>
                  </div>


                  <div class="form-group">
                    <label for="golongan_darah">Golongan Darah</label>
                    <input type="golongan_darah" class="form-control {{$errors->first('golongan_darah') ? 'is-invalid' : ''}}" name="golongan_darah" id="golongan_darah" placeholder="Enter Golongan Darah" value="{{ $pasien->golongan_darah }}">
                    <span class="error invalid-feedback">{{$errors->first('golongan_darah')}}</span>
                  </div>


                  <div class="form-group">
                    <label for="riwayat_medis">Nomor Riwayat Medis</label>
                    <input type="riwayat_medis" class="form-control {{$errors->first('riwayat_medis') ? 'is-invalid' : ''}}" name="riwayat_medis" id="riwayat_medis" placeholder="Enter Nomor Riwayat Medis" value="{{ $pasien->riwayat_medis }}">
                    <span class="error invalid-feedback">{{$errors->first('riwayat_medis')}}</span>
                  </div>

                  <div class="form-group">
                    <label for="alergi">Alergi</label>
                    <input type="alergi" class="form-control {{$errors->first('alergi') ? 'is-invalid' : ''}}" name="alergi" id="alergi" placeholder="Enter Tulis Alergi" value="{{ $pasien->alergi }}">
                    <span class="error invalid-feedback">{{$errors->first('alergi')}}</span>
                  </div>

                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">Update Pasien</button>
                </div>
              </form>
        </div>
      </div>
    </div>
  </div>
@endsection
