@extends('layouts.bootstrap')

@section('title')
Create Role User
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-warning">
        <div class="card-header">
            <h3>Create Role User</h3>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('role.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="form-group">
                        <label for="role_user">Nama Role</label>
                        <input type="text" class="form-control {{$errors->first('role_user') ? 'is-invalid' : ''}}" name="role_user" id="role_user" placeholder="Tulis nama Role" value="{{ old('role_user') }}">
                        <span class="error invalid-feedback">{{$errors->first('role_user')}}</span>
                    </div>

                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Simpan Data Sekarang</button>
                </div>
              </form>
        </div>
      </div>
    </div>
  </div>
@endsection
