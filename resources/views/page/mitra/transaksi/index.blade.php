@extends('layouts.master')

@push('styles')
<style>
    /* Cegah scroll horizontal saat modal terbuka */
    body.modal-open {
        overflow-x: hidden !important;
        padding-right: 0 !important;
    }

    /* Pastikan modal selalu di atas */
    .modal-backdrop {
        z-index: 1040 !important;
    }

    .modal {
        z-index: 1060 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 position-relative">
    {{-- Flash Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Card --}}
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Update Stok Mitra</h5>
            <a class="btn btn-primary" href="/dashboard/mitra/transaksi/history">Cek History</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach ($products as $item)
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <img src="{{ asset('/storage/app/public/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">{{ $item->product->name }}</h5>
                                    <p class="mb-1">Dagangan Dibawa: <strong>{{ $item->quantity }}</strong></p>
                                    <p class="mb-1">Shift: {{ $shift ?? '-' }}</p>
                                    <p class="mb-1">Kulakan Pagi: {{ $latestAddedStocks[$item->id] ?? 0 }}</p>
                                    <p class="mb-2">Harga Jual: <strong>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</strong></p>
                                    <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#stokModal{{ $item->id }}">
                                        Update Stok
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#productModal{{ $item->id }}">
                                        Detail Produk
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Update Stok --}}
                    <div class="modal fade" id="stokModal{{ $item->id }}" tabindex="-1" aria-labelledby="stokModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('mitra.stok.store', $item->product_id) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Stok - {{ $item->product->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="stok{{ $item->id }}" class="form-label">Jumlah Stok Sekarang</label>
                                            <input type="number" name="stock" id="stok{{ $item->id }}" class="form-control" min="0" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="shift{{ $item->id }}" class="form-label">Pilih Shift</label>
                                            <select name="shift" id="shift{{ $item->id }}" class="form-select" required>
                                                <option value="">-- Pilih Shift --</option>
                                                <option value="pagi">Pagi</option>
                                                <option value="sore">Sore</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Detail Produk --}}
                    <div class="modal fade" id="productModal{{ $item->id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Produk - {{ $item->product->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Nama Produk</th>
                                            <td>{{ $item->product->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kategori</th>
                                            <td>{{ $item->product->category }}</td>
                                        </tr>
                                        <tr>
                                            <th>Satuan</th>
                                            <td>{{ $item->product->satuan->nama_satuan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Harga Jual</th>
                                            <td>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Jual x Quantity</th>
                                            <td>Rp{{ number_format($item->product->price_sell * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Simpan Omset --}}
            <form action="{{ route('mitra.simpanOmset') }}" method="POST" class="mt-4 text-center">
                @csrf
                <small><span class="text-red">*</span>Simpan Seluruh Stock Hari ini Terlebih dahulu sebelum Simpan Omset Hari Ini</small>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Simpan Omset Hari Ini
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
