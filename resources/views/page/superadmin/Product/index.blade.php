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
            <h6>Daftar Products</h6>
            <div class="d-flex flex-column flex-md-row">
                <form method="GET" action="{{ route('admin.product') }}" class="mb-2 me-2">
                    <div class="d-flex">
                        <input style="width: 200px;" type="text" name="search" class="form-control me-2" placeholder="Cari..." value="{{ request('search') }}">
                        <select name="per_page" class="form-select me-2" onchange="this.form.submit()">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('admin.product.manage') }}" class="btn btn-primary">Tambah Product</a>
            </div>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="beli-tab" data-bs-toggle="tab" data-bs-target="#beli" type="button" role="tab" aria-controls="beli" aria-selected="true">
                        Product Beli ({{ $dataBeli->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="titipan-tab" data-bs-toggle="tab" data-bs-target="#titipan" type="button" role="tab" aria-controls="titipan" aria-selected="false">
                        Product Titipan ({{ $dataTitipan->total() }})
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="productTabContent">
                <!-- Tab Beli -->
                <div class="tab-pane fade show active" id="beli" role="tabpanel" aria-labelledby="beli-tab">
                    @php
                    $groupedBeli = $dataBeli->groupBy('category');
                    @endphp

                    @foreach($groupedBeli as $category => $products)
                    <div class="card mt-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0 text-uppercase">{{ $category ?? 'Tanpa Category' }} - BELI</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Price Buy</th>
                                            <th>Price Sell</th>
                                            <th>Category</th>
                                            <th>Satuan</th>
                                            <th>Laba</th>
                                            <th>Di Update</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($products) == 0)
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @else
                                        @foreach ($products as $sn)
                                        <tr class="text-center">
                                            <td>{{ (($dataBeli->currentPage() - 1) * $dataBeli->perPage()) + $loop->iteration }}</td>
                                            <td>{{ $sn->name }}</td>
                                            <td>
                                                @if($sn->image)
                                                    <img src="{{ asset('storage/' . $sn->image) }}" alt="{{ $sn->name }}" width="100">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>Rp. {{ number_format($sn->price, 0, ',', '.') }}</td>
                                            <td>Rp. {{ number_format($sn->price_sell, 0, ',', '.') }}</td>
                                            <td>{{ $sn->category ?? '-' }}</td>
                                            <td>{{ $sn->satuan->nama_satuan ?? '-' }}</td>
                                            <td>Rp. {{ number_format($sn->laba, 0, ',', '.') }}</td>
                                            <td>{{ $sn->updated_at ? $sn->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.product.manage', $sn->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('admin.product.delete', $sn->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Pagination untuk Beli -->
                    @if($dataBeli->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Page navigation Beli">
                            <ul class="pagination">
                                @if ($dataBeli->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataBeli->appends(array_merge(request()->input(), ['tab' => 'beli']))->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                @foreach ($dataBeli->getUrlRange(1, $dataBeli->lastPage()) as $page => $url)
                                    @if ($page == $dataBeli->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $dataBeli->appends(array_merge(request()->input(), ['tab' => 'beli']))->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                @if ($dataBeli->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataBeli->appends(array_merge(request()->input(), ['tab' => 'beli']))->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>

                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Menampilkan {{ $dataBeli->firstItem() }} sampai {{ $dataBeli->lastItem() }} dari {{ $dataBeli->total() }} hasil (Beli)
                        </small>
                    </div>
                    @endif
                </div>

                <!-- Tab Titipan -->
                <div class="tab-pane fade" id="titipan" role="tabpanel" aria-labelledby="titipan-tab">
                    @php
                    $groupedTitipan = $dataTitipan->groupBy('category');
                    @endphp

                    @foreach($groupedTitipan as $category => $products)
                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0 text-uppercase">{{ $category ?? 'Tanpa Category' }} - TITIPAN</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Price Buy</th>
                                            <th>Price Sell</th>
                                            <th>Category</th>
                                            <th>Satuan</th>
                                            <th>Laba</th>
                                            <th>Di Update</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($products) == 0)
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @else
                                        @foreach ($products as $sn)
                                        <tr class="text-center">
                                            <td>{{ (($dataTitipan->currentPage() - 1) * $dataTitipan->perPage()) + $loop->iteration }}</td>
                                            <td>{{ $sn->name }}</td>
                                            <td>
                                                @if($sn->image)
                                                    <img src="{{ asset('storage/' . $sn->image) }}" alt="{{ $sn->name }}" width="100">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>Rp. {{ number_format($sn->price, 0, ',', '.') }}</td>
                                            <td>Rp. {{ number_format($sn->price_sell, 0, ',', '.') }}</td>
                                            <td>{{ $sn->category ?? '-' }}</td>
                                            <td>{{ $sn->satuan->nama_satuan ?? '-' }}</td>
                                            <td>Rp. {{ number_format($sn->laba, 0, ',', '.') }}</td>
                                            <td>{{ $sn->updated_at ? $sn->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.product.manage', $sn->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('admin.product.delete', $sn->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Pagination untuk Titipan -->
                    @if($dataTitipan->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Page navigation Titipan">
                            <ul class="pagination">
                                @if ($dataTitipan->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataTitipan->appends(array_merge(request()->input(), ['tab' => 'titipan']))->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                @foreach ($dataTitipan->getUrlRange(1, $dataTitipan->lastPage()) as $page => $url)
                                    @if ($page == $dataTitipan->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $dataTitipan->appends(array_merge(request()->input(), ['tab' => 'titipan']))->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                @if ($dataTitipan->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataTitipan->appends(array_merge(request()->input(), ['tab' => 'titipan']))->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>

                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Menampilkan {{ $dataTitipan->firstItem() }} sampai {{ $dataTitipan->lastItem() }} dari {{ $dataTitipan->total() }} hasil (Titipan)
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mengatur tab aktif berdasarkan parameter URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');

    if (activeTab === 'titipan') {
        document.getElementById('beli-tab').classList.remove('active');
        document.getElementById('titipan-tab').classList.add('active');
        document.getElementById('beli').classList.remove('show', 'active');
        document.getElementById('titipan').classList.add('show', 'active');
    }
});
</script>

@endsection
