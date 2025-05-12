@extends('layouts.bootstrap')
@section('title')
Data Dokter
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
            <h3>Data Dokter</h3>
        </div>
        <div class="card-body table-responsive">
            @include('alert.success')
            @include('alert.failed')
            <br>
            <form method="GET" action="{{route('dokter.index')}}">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label><b>Search Email Dokter</b></label>
                        <input type="text" name="keyword" id="keyword" class="form-control" value="{{Request::get('keyword')}}">
                    </div>

                    <div class="col-md-3">
                        <label><b>Status Konsultasi</b></label>
                        <select name="konsultasi" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="OPEN" {{ Request::get('konsultasi') == 'OPEN' ? 'selected' : '' }}>BUKA</option>
                            <option value="CLOSE" {{ Request::get('konsultasi') == 'CLOSE' ? 'selected' : '' }}>TUTUP</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label><b>Status Reservasi</b></label>
                        <select name="reservasi" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="OPEN" {{ Request::get('reservasi') == 'OPEN' ? 'selected' : '' }}>BUKA</option>
                            <option value="CLOSE" {{ Request::get('reservasi') == 'CLOSE' ? 'selected' : '' }}>TUTUP</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary mr-2" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="{{ route('dokter.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </a>
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
                    <th>Nama Dokter</th>
                    <th>Jabatan</th>
                    <th>Spesialis</th>
                    <th>Konsultasi</th>
                    <th>Reservasi</th>
                    <th>Status</th>
                    <th>Biaya Konsultasi</th>
                    <th>Action</th>
                </tr>
		</thead>
                <tbody>
                    @foreach ($dokter as $row )
                    <tr>
                        <td>{{ $loop->iteration + ($dokter->perPage() * ($dokter->currentPage() - 1) ) }}</td>
                        <td>{{$row->users->name}}</td>
                        <td>
                            <span class="badge badge-primary"><i class="fas fa-user-md nav-icon mr-1"></i>{{$row->users->role}}</span>
                        </td>
                        <td>
                            {{$row->spesialis_name}}
                        </td>
                        <td>
                            @if ($row->konsultasi == 'CLOSE')
                            <span class="badge badge-secondary">TUTUP</span>
                            @else
                            <span class="badge badge-success">BUKA</span>
                            @endif
                        </td>
                        <td>
                            @if ($row->reservasi == 'CLOSE')
                            <span class="badge badge-secondary">TUTUP</span>
                            @else
                            <span class="badge badge-success">BUKA</span>
                            @endif
                        </td>
                        <td>
                            @if ($row->status_dokter == 'SIBUK')
                            <span class="badge badge-warning">SIBUK</span>
                            @else
                            <span class="badge badge-success">AKTIF</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-primary font-weight-bold">Rp {{ number_format($row->payment_konsultasi, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <a href="{{ route ('dokter.edit', [$row->id])}}" class="btn btn-warning btn-sm">Edit</a>
                             <a href="{{ route ('dokter.show', [$row->id])}}" class="btn btn-primary btn-sm">Detail Dokter</a>
                            <form action="{{ route('dokter.destroy', [$row->id]) }}" class="d-inline" method="POST" onsubmit="return confirm('Delete This Item ?')">
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
            {{$dokter->links()}}
        </div>
      </div>
    </div>
  </div>
@endsection
