@extends('layouts.master')
@section('title', 'Daftar Pengguna')
@section('content')
<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between">
            <h6>Daftar Pengguna</h6>
            <a href="{{ route('admin.user.manage') }}" class="btn btn-primary">Tambah User</a>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                <span class="alert-text"> {{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @php
                $groupedUsers = $users->groupBy('role.role');
            @endphp

            @foreach ($groupedUsers as $role => $group)
            <div class="card my-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">{{ $role ?? 'Tanpa Role' }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Supply Network</th>
                                    <th>Shift</th>
                                    <th>Dibuat</th>
                                    <th>Di Update</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->userTossa->name ?? 'Klasifikasi Non Supply' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-gradient-secondary">{{ $user->workShift->name ?? 'Tidak Punya Shift' }}</span>
                                    </td>
                                    <td class="text-center">{{ $user->created_at }}</td>
                                    <td class="text-center">{{ $user->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.user.manage', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
