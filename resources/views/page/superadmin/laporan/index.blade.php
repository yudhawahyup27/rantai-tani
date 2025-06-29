@extends('layouts.master')

@section('title', 'Laporan')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Laporan Keuangan Lengkap</h5>
            <a href="{{ route('admin.sewa.manage') }}" class="btn btn-primary">Create Sewa</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Nama Tossa</th>
                            <th>Bonus</th>
                            <th>Laba Buah</th>
                            <th>Laba Sayur</th>
                            <th>Laba Garingan</th>
                            <th>Passive Income</th>
                            <th>Total Laba Bahan Baku</th>
                            <th>Total Laba Keseluruhan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->user->username ?? '-' }}</td>
                                <td>{{ $item->user->userTossa->name ?? 'Tidak ada Tossa' }}</td>
                                <td>Rp {{ number_format($item->bonus, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->labaBuah, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->labaSayur, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->labaGaringan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->passiveIncome, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->totalLabaBahanBaku, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->totalLabaKeseluruhan, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.laporan.manage', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    {{-- Tambahkan aksi hapus jika dibutuhkan --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Belum ada data laporan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
