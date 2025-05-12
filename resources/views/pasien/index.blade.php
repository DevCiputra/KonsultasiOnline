@extends('layouts.bootstrap')
@section('title')
Data Pasien
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
            <h3>Data Pasien</h3>
        </div>
        <div class="card-body table-responsive">
            @include('alert.success')
            @include('alert.failed')
            <br>
            <form method="GET" action="{{route('pasien.index')}}">
                <div class="row">
                    <div class="col-2">
                        <b>Search Email Pasien</b>
                    </div>

                    <div class="col-3">
                        <input type="text" name="keyword" id="keyword" class="form-control" value="{{Request::get('keyword')}}">
                    </div>

                    <div class="col-4">
                        <button class="btn btn-default" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered">
            <br>
            <hr>
            {{-- <a href="{{route('product.create')}}" class="btn btn-success">+ Tambahkan Product</a> --}}
            <br>
            <hr>
		<thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Status</th>
                    <th>Golongan Darah</th>
                    <th>Nomor Medis</th>
                    <th>Gender</th>
                    <th>Alergi</th>
                    <th>Action</th>
                </tr>
		</thead>
                <tbody>
                    @foreach ($pasien as $row )
                    <tr>
                        <td>{{ $loop->iteration + ($pasien->perPage() * ($pasien->currentPage() - 1) ) }}</td>
                        <td>{{$row->users->name}}</td>
                        <td>
                            <span class="badge badge-primary"><i class="fas fa-user-injured mr-1"></i>{{$row->users->role}}</span>
                        </td>
                        <td>{{$row->golongan_darah}}</td>
                        <td>
                            <span class="badge badge-success"><i class="fas fa-history mr-1"></i>{{$row->riwayat_medis}}</span>
                        </td>
                        <td>{{$row->gender}}</td>
                        <td>{{$row->alergi}}</td>
                        <td>
                            <a href="{{ route ('pasien.edit', [$row->id])}}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route ('pasien.show', [$row->id])}}" class="btn btn-primary btn-sm">Detail</a>
                            <form action="{{ route('pasien.destroy', [$row->id]) }}" class="d-inline" method="POST" onsubmit="return confirm('Delete This Item ?')">
                                @csrf
                                {{method_field('DELETE')}}
                                <input type="submit" class="btn btn-danger btn-sm" value="Delete">
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{$pasien->links()}}
        </div>
      </div>
    </div>
  </div>
@endsection
