@extends('layouts.master')
@section('title', 'Daftar Supply Network')
@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="container-fluid py-4">

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="ni ni-like-2"></i></span>
        <span class="alert-text">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between">
            <h6>Filter Produk</h6>
            <div class="d-flex flex-column flex-md-row">
                <form method="GET" action="{{ route('admin.product') }}" class="mb-2 me-2">
                    <div class="d-flex">
                        <input style="width: 200px;" type="text" name="search" class="form-control me-2" placeholder="Cari..." value="{{ request('search') }}">
                        <select name="perpage" class="form-select me-2" onchange="this.form.submit()">
                            <option value="5" {{ request('perpage') == 5 ? 'selected' : '' }}>5</option>
                            <option value="25" {{ request('perpage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="100" {{ request('perpage') == 100 ? 'selected' : '' }}>100</option>
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

            {{-- Produk Beli --}}
            <h5 class="mt-4 px-4 text-primary">Produk Beli</h5>
            @php $groupedBuy = $productsBuy->groupBy('category'); @endphp
            @foreach ($groupedBuy as $category => $products)
                @include('admin.product._table', ['products' => $products, 'title' => $category ?? 'Tanpa Kategori'])
            @endforeach

            <div class="d-flex justify-content-center mt-3">
                {{ $productsBuy->appends(request()->query())->fragment('produk-beli')->links() }}
            </div>

            {{-- Produk Titipan --}}
            <h5 class="mt-5 px-4 text-success">Produk Titipan</h5>
            @php $groupedTitip = $productsTitip->groupBy('category'); @endphp
            @foreach ($groupedTitip as $category => $products)
                @include('admin.product._table', ['products' => $products, 'title' => $category ?? 'Tanpa Kategori'])
            @endforeach

            <div class="d-flex justify-content-center mt-3">
                {{ $productsTitip->appends(request()->query())->fragment('produk-titip')->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
