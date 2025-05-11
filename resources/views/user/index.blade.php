@extends('layouts.bootstrap')
@section('title')
Data Internal
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
            <h3>Data Internal</h3>
        </div>
        <div class="card-body table-responsive">
            @include('alert.success')
            @include('alert.failed')
            <br>
            <form method="GET" action="{{route('user.index')}}">
                <div class="row">
                    <div class="col-2">
                        <b>Search Email</b>
                    </div>

                    <div class="col-3">
                        <input type="text" name="keyword" id="keyword" class="form-control" value="{{Request::get('keyword')}}">
                    </div>

                    <div class="col-3">
                        <select name="role" id="role" class="form-control">
                            <option value="admin" @if (Request::get('role') == 'admin') selected @endif>Admin</option>
                            <option value="pasien" @if (Request::get('role') == 'pasien') selected @endif>Pasien</option>
                            <option value="dokter" @if (Request::get('role') == 'dokter') selected @endif>Dokter</option>
                            <option value="hbd" @if (Request::get('role') == 'hbd') selected @endif>HBD</option>
                            <option value="plp" @if (Request::get('role') == 'plp') selected @endif>PLP</option>
                        </select>
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
		<thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Avatar</th>
                    <th>Action</th>
                </tr>
		</thead>
                <tbody>
                    @foreach ($user as $row )
                    <tr>
                        <td>{{ $loop->iteration + ($user->perPage() * ($user->currentPage() - 1) ) }}</td>
                        <td>{{$row->name}}</td>
                        <td>{{$row->email}}</td>
                        <td>{{$row->role}}</td>
                        <td><img src="{{$row->avatar}}" alt="avatar" width="40px"></td>
                        <td>
                            <a href="{{ route ('user.edit', [$row->id])}}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('user.destroy', [$row->id]) }}" class="d-inline" method="POST" onsubmit="return confirm('Delete This Item ?')">
                                @csrf
                                {{method_field('DELETE')}}
                                <input type="submit" class="btn btn-danger btn-sm" value="Delete">
                            </form>
                            <a href="{{route('user.show', [$row->id])}}", class="btn btn-primary btn-sm">Detail Profile</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{$user->links()}}
        </div>
      </div>
    </div>
  </div>
@endsection
