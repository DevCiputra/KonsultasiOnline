@extends('layouts.bootstrap')
@section('title')
Role User
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
            <h3>Data Role User</h3>
        </div>
        <div class="card-body table-responsive">
            @include('alert.success')
            @include('alert.failed')
            <br>

            <form method="GET" action="{{route('role.index')}}">
                <div class="row">
                    <div class="col-2">
                        <b>Search Nama Role</b>
                    </div>
                    <div class="col-3 mb-3">
                        <input type="text" name="keyword" id="keyword" class="form-control" value="{{Request::get('keyword')}}">
                    </div>
                    <div class="col-4">
                        <button class="btn btn-default" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <br>
            <a href="{{route('role.create')}}" class="btn btn-success">+ Tambah Role</a>
            <hr>
            <table class="table table-bordered">
            <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Role</th>
                        <th>Action</th>
                    </tr>
            </thead>
                <tbody>
                    @foreach ($role as $row )
                    <tr>
                        <td>{{ $loop->iteration + ($role->perPage() * ($role->currentPage() - 1) ) }}</td>
                        <td>{{$row->role_user}}</td>
                        <td>
                            <a href="{{ route ('role.edit', [$row->id])}}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('role.destroy', [$row->id]) }}" class="d-inline" method="POST" onsubmit="return confirm('Delete This Item ?')">
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
            {{$role->links()}}
        </div>
      </div>
    </div>
  </div>
@endsection
