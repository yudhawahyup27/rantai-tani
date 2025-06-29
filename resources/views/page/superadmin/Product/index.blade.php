@extends('layouts.master')
@section('title', 'Daftar Supply Network')
@section('content')
@php
    use Illuminate\Support\Str;
@endphp
<div class="container-fluid py-4">
    <div class="card mb-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                <span class="alert-text"> {{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between">
            <h6>Daftar Produk</h6>
            <div class="d-flex flex-column flex-md-row">
                <form method="GET" action="{{ route('admin.product') }}" class="mb-2 me-2">
                    <div class="d-flex">
                        <input style="width: 200px;" type="text" name="search" class="form-control me-2" placeholder="Cari..." value="{{ request('search') }}">
                        <select name="perpage" class="form-select me-2" onchange="this.form.submit()">
                            <option value="5" {{ request('perpage', 5) == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('perpage', 5) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perpage', 5) == 25 ? 'selected' : '' }}>25</option>
                            <option value="100" {{ request('perpage', 5) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="asc" {{ request('sort', 'asc') == 'asc' ? 'selected' : '' }}>Terlama</option>
                            <option value="desc" {{ request('sort', 'asc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('admin.product.manage') }}" class="btn btn-primary">Tambah Produk</a>
            </div>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            {{-- Debug Info --}}
            @if(config('app.debug'))
                <div class="alert alert-info">
                    <small>
                        Debug: Produk Beli: {{ $productsBuy->total() ?? 0 }} |
                        Produk Titip: {{ $productsTitip->total() ?? 0 }} |
                        Search: {{ request('search') ?? 'none' }} |
                        PerPage: {{ request('perpage', 5) }} |
                        Sort: {{ request('sort', 'asc') }}
                    </small>
                </div>
            @endif

            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="beli-tab" data-bs-toggle="tab" href="#beli" role="tab">
                        Produk Beli ({{ $productsBuy->total() ?? 0 }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="titip-tab" data-bs-toggle="tab" href="#titip" role="tab">
                        Produk Titipan ({{ $productsTitip->total() ?? 0 }})
                    </a>
                </li>
            </ul>
            <div class="tab-content pt-3" id="productTabsContent">

                {{-- Tab Produk Beli --}}
                <div class="tab-pane fade show active" id="beli" role="tabpanel">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th class="sticky-column" style="left: 0; z-index: 1; background: white;">Nama</th>
                                    <th>Gambar</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Laba</th>
                                    <th>Diupdate</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productsBuy as $index => $sn)
                                    <tr class="text-center">
                                        <td>{{ ($productsBuy->currentPage() - 1) * $productsBuy->perPage() + $index + 1 }}</td>
                                        <td class="sticky-column" style="left: 0; z-index: 1; background: white;">{{ $sn->name }}</td>
                                        <td>
                                            @if($sn->image)
                                                <img src="{{ asset('storage/' . $sn->image) }}" alt="{{ $sn->name }}" width="80" class="img-thumbnail">
                                            @else
                                                <span class="text-muted badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td>Rp. {{ number_format($sn->price ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($sn->price_sell ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $sn->category ?? '-' }}</td>
                                        <td>{{ $sn->satuan->nama_satuan ?? '-' }}</td>
                                        <td>Rp. {{ number_format($sn->laba ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $sn->updated_at ? $sn->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.product.manage', $sn->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('admin.product.delete', $sn->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus {{ $sn->name }}?')">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                @if(request('search'))
                                                    Tidak ada produk beli yang ditemukan untuk pencarian "{{ request('search') }}"
                                                @else
                                                    Belum ada produk beli yang ditambahkan
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($productsBuy->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $productsBuy->appends(request()->query())->fragment('beli')->links() }}
                        </div>
                    @endif
                </div>

                {{-- Tab Produk Titipan --}}
                <div class="tab-pane fade" id="titip" role="tabpanel">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th class="sticky-column" style="left: 0; z-index: 1; background: white;">Nama</th>
                                    <th>Gambar</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Laba</th>
                                    <th>Diupdate</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productsTitip as $index => $sn)
                                    <tr class="text-center">
                                        <td>{{ ($productsTitip->currentPage() - 1) * $productsTitip->perPage() + $index + 1 }}</td>
                                        <td class="sticky-column" style="left: 0; z-index: 1; background: white;">{{ $sn->name }}</td>
                                        <td>
                                            @if($sn->image)
                                                <img src="{{ asset('storage/' . $sn->image) }}" alt="{{ $sn->name }}" width="80" class="img-thumbnail">
                                            @else
                                                <span class="text-muted badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td>Rp. {{ number_format($sn->price ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($sn->price_sell ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $sn->category ?? '-' }}</td>
                                        <td>{{ $sn->satuan->nama_satuan ?? '-' }}</td>
                                        <td>Rp. {{ number_format($sn->laba ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $sn->updated_at ? $sn->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.product.manage', $sn->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('admin.product.delete', $sn->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus {{ $sn->name }}?')">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                @if(request('search'))
                                                    Tidak ada produk titipan yang ditemukan untuk pencarian "{{ request('search') }}"
                                                @else
                                                    Belum ada produk titipan yang ditambahkan
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($productsTitip->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $productsTitip->appends(request()->query())->fragment('titip')->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- JavaScript untuk mengingat tab aktif --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah ada fragment di URL
    if (window.location.hash) {
        const hash = window.location.hash;
        if (hash === '#titip') {
            // Aktifkan tab titip
            const titipTab = document.getElementById('titip-tab');
            const beliTab = document.getElementById('beli-tab');
            const titipPane = document.getElementById('titip');
            const beliPane = document.getElementById('beli');

            if (titipTab && beliTab && titipPane && beliPane) {
                // Hapus active dari tab beli
                beliTab.classList.remove('active');
                beliPane.classList.remove('show', 'active');

                // Aktifkan tab titip
                titipTab.classList.add('active');
                titipPane.classList.add('show', 'active');
            }
        }
    }
});
</script>
@endsection
