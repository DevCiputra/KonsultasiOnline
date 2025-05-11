@extends('layouts.bootstrap')

@section('title')
Edit Role
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
            <h3>Edit {{$role->role_user}}</h3>
        </div>
        <div class="card-body">
            <hr>
            <a href="{{route('role.index')}}" class="btn btn-secondary">Back</a>
            <hr>
            <form method="POST" action="{{ route('role.update', [$role->id]) }}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}



                <div class="form-group">
                    <label for="role_user">Nama Role</label>
                    <input type="text" class="form-control {{$errors->first('role_user') ? 'is-invalid' : ''}}" name="role_user" id="role_user" placeholder="Kategori" value="{{$role->role_user}}">
                    <span class="error invalid-feedback">{{$errors->first('role_user')}}</span>
                </div>


                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">Update Role Sekarang</button>
                </div>
              </form>
        </div>
      </div>
    </div>
  </div>
@endsection
