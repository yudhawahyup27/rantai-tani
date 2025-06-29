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

        <div class="card-header pb-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                <h6>Daftar Products</h6>
                <div class="d-flex flex-column flex-md-row">
                    <form method="GET" action="{{ route('admin.product') }}" class="mb-2 me-2" id="filterForm">
                        <input type="hidden" name="tab" id="tabInput" value="{{ request('tab', 'titipan') }}">
                        <div class="d-flex">
                            <input style="width: 200px;" type="text" name="search" class="form-control me-2"
                                   placeholder="Cari..." value="{{ request('search') }}">
                            <select name="per_page" class="form-select me-2" onchange="document.getElementById('filterForm').submit()">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <select name="sort" class="form-select me-2" onchange="document.getElementById('filterForm').submit()">
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
                            </select>
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    <a href="{{ route('admin.product.manage') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Product
                    </a>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ request('tab', 'titipan') == 'titipan' ? 'active' : '' }}"
                       href="#titipan" data-bs-toggle="tab" data-tab="titipan" onclick="switchTab('titipan')">
                        <i class="fas fa-handshake me-1"></i>
                        Produk Titipan
                        <span class="badge bg-primary ms-1">{{ $titipanData->total() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') == 'beli' ? 'active' : '' }}"
                       href="#beli" data-bs-toggle="tab" data-tab="beli" onclick="switchTab('beli')">
                        <i class="fas fa-shopping-cart me-1"></i>
                        Produk Beli
                        <span class="badge bg-success ms-1">{{ $beliData->total() }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body px-0 pt-3 pb-2">
            <div class="tab-content">
                {{-- Tab Titipan --}}
                <div class="tab-pane fade {{ request('tab', 'titipan') == 'titipan' ? 'show active' : '' }}" id="titipan">
                    @if($titipanData->count() > 0)
                        @php $currentCategory = null; @endphp

                        @foreach($titipanData as $index => $product)
                            @if($currentCategory !== $product->category)
                                @if($currentCategory !== null)
                                    {{-- Close previous category --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                                @endif

                                @php $currentCategory = $product->category; @endphp

                                {{-- Start new category --}}
                                <div class="card mb-3 border-start border-primary border-4">
                                    <div class="card-header bg-gradient-primary text-white">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-handshake me-2"></i>
                                            <h6 class="mb-0 text-uppercase">{{ $currentCategory ?? 'Tanpa Category' }} - TITIPAN</h6>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table align-items-center mb-0">
                                                <thead class="thead-light">
                                                    <tr class="text-center">
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price Buy</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price Sell</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Satuan</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Laba</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Update</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            @endif

                            <tr class="text-center">
                                <td><span class="text-xs font-weight-bold">{{ $titipanData->firstItem() + $index }}</span></td>
                                <td><span class="text-xs font-weight-bold">{{ $product->name }}</span></td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="rounded" width="80" height="80" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><span class="text-xs font-weight-bold text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</span></td>
                                <td><span class="text-xs font-weight-bold text-primary">Rp {{ number_format($product->price_sell, 0, ',', '.') }}</span></td>
                                <td><span class="text-xs">{{ $product->satuan->nama_satuan ?? '-' }}</span></td>
                                <td><span class="text-xs font-weight-bold text-warning">Rp {{ number_format($product->laba, 0, ',', '.') }}</span></td>
                                <td><span class="text-xs">{{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : '-' }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.product.manage', $product->id) }}"
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus {{ $product->name }}?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Close last category --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Pagination for Titipan --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 px-3">
                            <div>
                                <span class="text-sm text-muted">
                                    Menampilkan {{ $titipanData->firstItem() ?? 0 }} sampai {{ $titipanData->lastItem() ?? 0 }}
                                    dari {{ $titipanData->total() }} produk titipan
                                </span>
                            </div>
                            <div>
                                {{ $titipanData->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada produk titipan</h5>
                            <p class="text-muted">Produk titipan akan muncul di sini</p>
                        </div>
                    @endif
                </div>

                {{-- Tab Beli --}}
                <div class="tab-pane fade {{ request('tab') == 'beli' ? 'show active' : '' }}" id="beli">
                    @if($beliData->count() > 0)
                        @php $currentCategory = null; @endphp

                        @foreach($beliData as $index => $product)
                            @if($currentCategory !== $product->category)
                                @if($currentCategory !== null)
                                    {{-- Close previous category --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                                @endif

                                @php $currentCategory = $product->category; @endphp

                                {{-- Start new category --}}
                                <div class="card mb-3 border-start border-success border-4">
                                    <div class="card-header bg-gradient-success text-white">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            <h6 class="mb-0 text-uppercase">{{ $currentCategory ?? 'Tanpa Category' }} - BELI</h6>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table align-items-center mb-0">
                                                <thead class="thead-light">
                                                    <tr class="text-center">
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price Buy</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price Sell</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Satuan</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Laba</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Update</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                            @endif

                            <tr class="text-center">
                                <td><span class="text-xs font-weight-bold">{{ $beliData->firstItem() + $index }}</span></td>
                                <td><span class="text-xs font-weight-bold">{{ $product->name }}</span></td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="rounded" width="80" height="80" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><span class="text-xs font-weight-bold text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</span></td>
                                <td><span class="text-xs font-weight-bold text-primary">Rp {{ number_format($product->price_sell, 0, ',', '.') }}</span></td>
                                <td><span class="text-xs">{{ $product->satuan->nama_satuan ?? '-' }}</span></td>
                                <td><span class="text-xs font-weight-bold text-warning">Rp {{ number_format($product->laba, 0, ',', '.') }}</span></td>
                                <td><span class="text-xs">{{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : '-' }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.product.manage', $product->id) }}"
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus {{ $product->name }}?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Close last category --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Pagination for Beli --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 px-3">
                            <div>
                                <span class="text-sm text-muted">
                                    Menampilkan {{ $beliData->firstItem() ?? 0 }} sampai {{ $beliData->lastItem() ?? 0 }}
                                    dari {{ $beliData->total() }} produk beli
                                </span>
                            </div>
                            <div>
                                {{ $beliData->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada produk beli</h5>
                            <p class="text-muted">Produk beli akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    document.getElementById('tabInput').value = tabName;

    // Update URL without refreshing page
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);
}

// Handle pagination clicks to maintain active tab
document.addEventListener('DOMContentLoaded', function() {
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const url = new URL(this.href);
            const activeTab = document.querySelector('.nav-link.active').getAttribute('data-tab');
            url.searchParams.set('tab', activeTab);
            this.href = url.toString();
        });
    });
});
</script>

@endsection
