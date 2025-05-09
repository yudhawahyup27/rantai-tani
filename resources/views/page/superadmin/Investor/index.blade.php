@extends('layouts.master')
@section('title', 'Daftar Investor')

@section('content')

<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between">
            <h6>Daftar Investor</h6>
            <a href="{{ route('admin.investor.manage') }}" class="btn btn-primary">Tambah Investor</a>
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
                            <th>No</th>
                            <th>Investor</th>
                            <th>Supply Network</th>
                            <th>Lot</th>
                            <th>Total Value</th>
                            <th>Dividen </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(count($data) == 0)
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <h6 class="text-muted">Tidak ada data investor</h6>
                            </td>
                        </tr>
                    @else
                        @foreach ($data as $investor)
                        <tr>
                            <td class="text-center">
                                <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                            </td>
                            <td class="text-center">
                                <h6 class="mb-0 text-sm">{{ $investor->user->username }}</h6>
                            </td>
                            <td class="text-center">
                                <h6 class="mb-0 text-sm">{{ $investor->tossaName() }}</h6>
                            </td>
                            <td class="text-center">
                                <h6 class="mb-0 text-sm">{{ $investor->perlot }}</h6>
                            </td>
                            <td class="text-center">
                                <h6 class="mb-0 text-sm">Rp {{ number_format($investor->total, 0, ',', '.') }}</h6>
                            </td>
                            <td class="text-center">
                                <h6 class="mb-0 text-sm">Rp {{ number_format($investor->Deviden, 0, ',', '.') }}</h6>
                            </td>
                            <td class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.investor.manage', $investor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.investor.delete', $investor->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
