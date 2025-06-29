@extends('layouts.master');

@section('title','Sewa')

@section('content')
<div class="container" >
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title">
                Sewa Supply Network
            </div>
            <div>
                <a class="bg-primary text-white p-2 rounded" href="{{ route('admin.sewa.manage') }}">Create Laporan </a>
            </div>
        </div>
        <div class="card-body">
               @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Nama Tossa</th>
                <th>Harga Sewa</th>
                <th>Tanggal Sewa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->user->username ?? '-' }}</td>
                    <td>{{ $item->user->userTossa->name ?? 'Tidak ada Tossa' }}</td>
                    <td>Rp {{ number_format($item->hargaSewa, 0, ',', '.') }}</td>
                    <td>{{ $item->created_at ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.sewa.manage', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.sewa.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data sewa</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
        </div>
    </div>
</div>
@endsection
