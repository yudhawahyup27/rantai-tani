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
            @if($data->count() > 0)
                @php
                    $currentCategory = null;
                @endphp

                @foreach($data as $index => $sn)
                    {{-- Check if we need to start a new category section --}}
                    @if($currentCategory !== $sn->category)
                        @if($currentCategory !== null)
                            {{-- Close previous category table --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        @endif

                        @php $currentCategory = $sn->category; @endphp

                        {{-- Start new category section --}}
                        <div class="card mb-3">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0 text-uppercase">{{ $currentCategory ?? 'Tanpa Category' }}</h6>
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
                    @endif

                    {{-- Product row --}}
                    <tr class="text-center">
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $sn->name }}</td>
                        <td>
                            @if($sn->image)
                                <img src="{{ asset('/storage/app/public/' . $sn->image) }}" alt="{{ $sn->name }}" width="100">
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

                {{-- Close the last category table --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $data->links() }}
        </div>

            @else
                <div class="text-center py-4">
                    <p class="text-muted">Tidak ada data produk yang ditemukan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
