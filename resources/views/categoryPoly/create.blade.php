@extends('layouts.bootstrap')

@section('title')
Create Kategori Polyclinic
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-warning">
        <div class="card-header">
            <h3>Create Kategori Polyclinic</h3>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('categoryPoly.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="form-group">
                        <label for="category_polyclinic">Nama Kategori Polyclinic</label>
                        <input type="text" class="form-control {{$errors->first('category_polyclinic') ? 'is-invalid' : ''}}" name="category_polyclinic" id="category_polyclinic" placeholder="Tulis nama Kategori Polyclinic" value="{{ old('category_polyclinic') }}">
                        <span class="error invalid-feedback">{{$errors->first('category_polyclinic')}}</span>
                    </div>

                    <div class="form-group">
                        <label for="image_category_poly">Photo Kategori Polyclinic</label>
                        <input type="file" class="form-control-file {{$errors->first('image_category_poly') ? 'is-invalid' : ''}}" name="image_category_poly" id="image_category_poly">
                        <span class="error invalid-feedback">{{$errors->first('image_category_poly')}}</span>
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
