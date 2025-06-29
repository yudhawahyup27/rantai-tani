@extends('layouts.master')

@section('title', 'Riwayat Penambahan Stok')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Riwayat Penambahan Stok</h3>

            <h4>Produk: {{ $stock->product->name ?? '-' }}</h4>
            <h5>Supply Network: {{ $stock->tossa->name ?? '-' }}</h5>
            <h6>Jumlah Stok Saat Ini: {{ $stock->quantity }}</h6>

            <table class="table  mt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jumlah Penambahan</th>
                        <th>Tanggal Penambahan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stock->newStock as $index => $newStock)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $newStock->quantity_added }}</td>
                            <td>{{ $newStock->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada riwayat penambahan stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <a href="{{ route('admin.stock') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Stok</a>
        </div>
    </div>
</div>
@endsection
