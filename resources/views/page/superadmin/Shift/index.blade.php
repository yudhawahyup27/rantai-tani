@extends('layouts.master')
@section('title', 'Daftar Shift')
@section('content')
<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between">
            <h6>Daftar Shift</h6>
            <a href="{{ route('admin.shift.manage') }}" class="btn btn-primary">Tambah Shift</a>
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
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr class="text-center">
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Shift</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Time</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Time</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dibuat</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Di Update </th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                </tr>
              </thead>
              <tbody>
@foreach ($data as $sn )

                    <tr>
                        <td>   <h6 class="mb-0 text-sm text-center">{{  $loop->iteration  }}</h6></td>
                        <td class="text-center">




                              <h6 class="mb-0 text-sm">{{ $sn->name }}</h6>

                        </td>
                        <td class="text-center">




                              <h6 class="mb-0 text-sm">{{ $sn->start_time }}</h6>

                        </td>
                        <td class="text-center">




                              <h6 class="mb-0 text-sm">{{ $sn->end_time }}</h6>

                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-xs font-weight-bold">{{ $sn->created_at ?? '-'  }}</span>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-xs font-weight-bold">{{ $sn->updated_at ?? '-' }}</span>
                        </td>
                        <td class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.shift.manage', $sn->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.shift.delete', $sn->id) }}" method="POST" class="d-inline">
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
</div>
@endsection
