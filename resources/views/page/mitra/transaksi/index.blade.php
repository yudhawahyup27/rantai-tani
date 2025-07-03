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

    /* Style untuk tab */
    .nav-tabs .nav-link {
        color: #495057;
        border-bottom: 2px solid transparent;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #007bff;
    }

    .nav-tabs .nav-link.active {
        color: #007bff;
        border-bottom-color: #007bff;
        background-color: transparent;
        border-top: none;
        border-left: none;
        border-right: none;
    }

    .tab-content {
        padding-top: 20px;
    }

    /* Badge untuk jumlah item */
    .badge-count {
        background-color: #6c757d;
        color: white;
        font-size: 0.75rem;
        padding: 0.2em 0.5em;
        border-radius: 50%;
        margin-left: 5px;
    }

    .nav-tabs .nav-link.active .badge-count {
        background-color: #007bff;
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
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="sayur-beli-tab" data-bs-toggle="tab" data-bs-target="#sayur-beli" type="button" role="tab">
                        Sayur Beli
                        <span class="badge-count">{{ $categoryCount['sayur_beli'] ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sayur-titipan-tab" data-bs-toggle="tab" data-bs-target="#sayur-titipan" type="button" role="tab">
                        Sayur Titipan
                        <span class="badge-count">{{ $categoryCount['sayur_titipan'] ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="buah-beli-tab" data-bs-toggle="tab" data-bs-target="#buah-beli" type="button" role="tab">
                        Buah Beli
                        <span class="badge-count">{{ $categoryCount['buah_beli'] ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="buah-titipan-tab" data-bs-toggle="tab" data-bs-target="#buah-titipan" type="button" role="tab">
                        Buah Titipan
                        <span class="badge-count">{{ $categoryCount['buah_titipan'] ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="garingan-beli-tab" data-bs-toggle="tab" data-bs-target="#garingan-beli" type="button" role="tab">
                        Garingan Beli
                        <span class="badge-count">{{ $categoryCount['garingan_beli'] ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="garingan-titipan-tab" data-bs-toggle="tab" data-bs-target="#garingan-titipan" type="button" role="tab">
                        Garingan Titipan
                        <span class="badge-count">{{ $categoryCount['garingan_titipan'] ?? 0 }}</span>
                    </button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content" id="categoryTabsContent">
                {{-- Sayur Beli --}}
                <div class="tab-pane fade show active" id="sayur-beli" role="tabpanel">
                    <div class="row g-3">
                        @forelse ($productsByCategory['sayur_beli'] ?? [] as $item)
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
                                            <p class="mb-2">Harga Beli: <strong>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</strong></p>
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
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Tidak ada produk Sayur Beli
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Sayur Titipan --}}
                <div class="tab-pane fade" id="sayur-titipan" role="tabpanel">
                    <div class="row g-3">
                        @forelse ($productsByCategory['sayur_titipan'] ?? [] as $item)
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
                                            <p class="mb-2">Harga Beli: <strong>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</strong></p>
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
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Tidak ada produk Sayur Titipan
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Buah Beli --}}
                <div class="tab-pane fade" id="buah-beli" role="tabpanel">
                    <div class="row g-3">
                        @forelse ($productsByCategory['buah_beli'] ?? [] as $item)
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
                                            <p class="mb-2">Harga Beli: <strong>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</strong></p>
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
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Tidak ada produk Buah Beli
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Buah Titipan --}}
                <div class="tab-pane fade" id="buah-titipan" role="tabpanel">
                    <div class="row g-3">
                        @forelse ($productsByCategory['buah_titipan'] ?? [] as $item)
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
                                            <p class="mb-2">Harga Beli: <strong>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</strong></p>
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
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Tidak ada produk Buah Titipan
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Garingan Beli --}}
                <div class="tab-pane fade" id="garingan-beli" role="tabpanel">
                    <div class="row g-3">
                        @forelse ($productsByCategory['garingan_beli'] ?? [] as $item)
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
                                            <p class="mb-2">Harga Beli: <strong>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</strong></p>
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
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Tidak ada produk Garingan Beli
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Garingan Titipan --}}
                <div class="tab-pane fade" id="garingan-titipan" role="tabpanel">
                    <div class="row g-3">
                        @forelse ($productsByCategory['garingan_titipan'] ?? [] as $item)
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
                                            <p class="mb-2">Harga Beli: <strong>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</strong></p>
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
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Tidak ada produk Garingan Titipan
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
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

{{-- Modal untuk semua produk --}}
@if(isset($products))
    @foreach ($products as $item)
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
                                <th>Harga Beli</th>
                                <td>Rp{{ number_format($item->product->price_sell, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Harga Rekomendasi</th>
                                <td>Rp{{ number_format($item->product->harga_rekomendasi, 0, ',', '.') }}</td>
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
@endif
@endsection
