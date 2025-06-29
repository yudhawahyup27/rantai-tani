<?php
// Controller Method
public function index(Request $request){
    $search = $request->input('search');
    $sort = $request->input('sort', 'asc');
    $perPage = $request->input('per_page', 5);

    // Base query with search
    $baseQuery = Product::with('satuan');

    if ($search) {
        $baseQuery->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('price', 'like', '%' . $search . '%')
              ->orWhere('category', 'like', '%' . $search . '%');
        });
    }

    // Clone query for each type
    $titipanQuery = clone $baseQuery;
    $beliQuery = clone $baseQuery;

    // Get data for each type with separate pagination
    $titipanData = $titipanQuery
        ->where('jenis', 'titipan')
        ->orderBy('category', 'asc')
        ->orderBy('name', $sort)
        ->paginate($perPage, ['*'], 'titipan_page');

    $beliData = $beliQuery
        ->where('jenis', 'beli')
        ->orderBy('category', 'asc')
        ->orderBy('name', $sort)
        ->paginate($perPage, ['*'], 'beli_page');

    // Append search parameters to pagination
    $titipanData->appends($request->except('titipan_page'));
    $beliData->appends($request->except('beli_page'));

    return view('page.superadmin.Product.index', compact('titipanData', 'beliData'));
}
?>

{{-- Updated Blade Template --}}
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
                <div class="mb-2">
                    <h6>Daftar Products</h6>
                    <div class="d-flex gap-3 mt-2">
                        <span class="badge bg-primary px-3 py-2">
                            <i class="fas fa-handshake me-1"></i>
                            Titipan: {{ $titipanData->total() }}
                        </span>
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Beli: {{ $beliData->total() }}
                        </span>
                    </div>
                </div>
                <div class="d-flex flex-column flex-md-row">
                    <form method="GET" action="{{ route('admin.product') }}" class="mb-2 me-2">
                        <div class="d-flex">
                            <input style="width: 200px;" type="text" name="search" class="form-control me-2"
                                   placeholder="Cari produk..." value="{{ request('search') }}">
                            <select name="per_page" class="form-select me-2" onchange="this.form.submit()">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <select name="sort" class="form-select me-2" onchange="this.form.submit()">
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
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            {{-- SECTION PRODUK TITIPAN --}}
            <div class="mb-5">
                <div class="d-flex align-items-center mb-3 px-3">
                    <div class="bg-primary rounded-circle p-2 me-3">
                        <i class="fas fa-handshake text-white"></i>
                    </div>
                    <h5 class="mb-0 text-primary">PRODUK TITIPAN</h5>
                </div>

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
                            <div class="card mb-3 mx-3 border-start border-primary border-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-uppercase text-primary">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $currentCategory ?? 'Tanpa Category' }}
                                    </h6>
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
                                         class="rounded shadow-sm" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm"
                                         style="width: 60px; height: 60px;">
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

                    {{-- Close last category for titipan --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination for Titipan --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 px-3">
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
                    <div class="text-center py-4 mx-3">
                        <div class="card bg-light">
                            <div class="card-body py-5">
                                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada produk titipan</h6>
                                <p class="text-muted mb-0">Produk titipan akan muncul di sini</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- SECTION PRODUK BELI --}}
            <div class="mb-3">
                <div class="d-flex align-items-center mb-3 px-3">
                    <div class="bg-success rounded-circle p-2 me-3">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                    <h5 class="mb-0 text-success">PRODUK BELI</h5>
                </div>

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
                            <div class="card mb-3 mx-3 border-start border-success border-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-uppercase text-success">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $currentCategory ?? 'Tanpa Category' }}
                                    </h6>
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
                                         class="rounded shadow-sm" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm"
                                         style="width: 60px; height: 60px;">
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

                    {{-- Close last category for beli --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination for Beli --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 px-3">
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
                    <div class="text-center py-4 mx-3">
                        <div class="card bg-light">
                            <div class="card-body py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada produk beli</h6>
                                <p class="text-muted mb-0">Produk beli akan muncul di sini</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
