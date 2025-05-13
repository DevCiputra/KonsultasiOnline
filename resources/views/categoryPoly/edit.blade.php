@extends('layouts.bootstrap')

@section('title')
Edit Kategori
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
            <h3>Edit {{$categoryPoly->category_polyclinic}}</h3>
        </div>
        <div class="card-body">
            <hr>
            <a href="{{route('categoryPoly.index')}}" class="btn btn-secondary">Back</a>
            <hr>
            <form method="POST" action="{{ route('categoryPoly.update', [$categoryPoly->id]) }}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}



                <div class="form-group">
                    <label for="category_polyclinic">Nama Kategori</label>
                    <input type="text" class="form-control {{$errors->first('category_polyclinic') ? 'is-invalid' : ''}}" name="category_polyclinic" id="category_polyclinic" placeholder="Kategori" value="{{$categoryPoly->category_polyclinic}}">
                    <span class="error invalid-feedback">{{$errors->first('category_polyclinic')}}</span>
                </div>

                 <div class="form-group">
                    <label for="image_category_poly">Photo Kategori Polyclinic</label>
                    <input type="file" class="form-control {{$errors->first('image_category_poly') ? 'is-invalid' : ''}}"
                    name="image_category_poly" id="image_category_poly">
                    <span class="error invalid-feedback">{{$errors->first('image_category_poly')}}</span>
                  </div>


                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-success">Update Kategori Sekarang</button>
                </div>
              </form>
        </div>
      </div>
    </div>
  </div>
@endsection
