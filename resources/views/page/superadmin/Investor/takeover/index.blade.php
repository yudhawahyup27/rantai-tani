@extends('layouts.master')

@section('title', 'Daftar Takeover Saham')
@section('content')
<div class="container">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Takeover Saham</h4>
            <a href="{{ route('admin.takeover.manage') }}" class="btn btn-primary">Tambah Takeover</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Dari Investor</th>
                        <th>Ke Pengguna</th>
                        <th>Saham</th>
                        <th>Jumlah Lot</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->fromUser->username ?? '-' }}</td>
                        <td>{{ $item->toUser->username ?? '-' }}</td>
                        <td>{{ $item->tossa->name }}</td>
                        <td>{{ $item->perlot }}</td>
                        <td>{{ number_format($item->harga_takeover, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.takeover.manage', $item->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('admin.takeover.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data takeover</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
