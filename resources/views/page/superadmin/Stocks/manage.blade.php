@extends('layouts.master')

@section('title', $data->exists ? 'Edit Stok' : 'Tambah Stok')

@section('content')
<div class="container mt-4">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2>{{ $data->exists ? 'Edit Data Stok' : 'Tambah Data Stok' }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ $data->exists ? route('admin.stock.update', $data->id) : route('admin.stock.store') }}" method="POST">
                @csrf
                @if($data->exists)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="product_id" class="form-label">Pilih Produk</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($product as $productItem)
                            <option value="{{ $productItem->id }}" {{ $productItem->id == $data->product_id ? 'selected' : '' }}>
                                {{ $productItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tossa_id" class="form-label">Pilih Supply Network</label>
                    <select name="tossa_id" class="form-select" required>
                        <option value="">-- Pilih Network --</option>
                        @foreach ($tossa as $tossaItem)
                            <option value="{{ $tossaItem->id }}" {{ $tossaItem->id == $data->tossa_id ? 'selected' : '' }}>
                                {{ $tossaItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tambah hanya saat edit --}}
                @if ($data->exists)
                    <div class="mb-3">
                        <label for="quantity_new" class="form-label">Jumlah Stok Baru</label>
                        <input type="number" step="0.01" name="quantity_new" class="form-control"
                            value="{{ old('quantity_new', $data->quantity_new) }}">
                    </div>
                @endif

                <div class="mb-3">
                    <label for="quantity" class="form-label">Jumlah Stok</label>
                    <input type="number" step="0.01" name="quantity" class="form-control"
                        value="{{ old('quantity', $data->quantity) }}" required>
                    @if ($data->exists)
                        <small class="text-danger">*Hanya diubah jika ada koreksi jumlah stok</small>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">{{ $data->exists ? 'Update' : 'Simpan' }}</button>
                <a href="{{ route('admin.stock') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.master')

@section('title', $data->exists ? 'Edit Stok' : 'Tambah Stok')

@section('content')
<div class="container mt-4">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2>{{ $data->exists ? 'Edit Data Stok' : 'Tambah Data Stok' }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ $data->exists ? route('admin.stock.update', $data->id) : route('admin.stock.store') }}" method="POST">
                @csrf
                @if($data->exists)
                    @method('PUT')
                @endif

                {{-- Hanya satu supply network untuk semua produk --}}
                <div class="mb-3">
                    <label for="tossa_id" class="form-label">Pilih Supply Network</label>
                    <select name="tossa_id" class="form-select" required>
                        <option value="">-- Pilih Network --</option>
                        @foreach ($tossa as $tossaItem)
                            <option value="{{ $tossaItem->id }}" {{ $tossaItem->id == $data->tossa_id ? 'selected' : '' }}>
                                {{ $tossaItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Saat EDIT hanya tampil 1 produk --}}
                @if($data->exists)
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($product as $productItem)
                                <option value="{{ $productItem->id }}" {{ $productItem->id == $data->product_id ? 'selected' : '' }}>
                                    {{ $productItem->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantity_new" class="form-label">Tambah Stok Baru</label>
                        <input type="number" name="quantity_new" step="0.01" class="form-control"
                               value="{{ old('quantity_new', $data->quantity_new) }}">
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah Stok Total</label>
                        <input type="number" name="quantity" step="0.01" class="form-control"
                               value="{{ old('quantity', $data->quantity) }}" required>
                        <small class="text-danger">*Ubah hanya jika perlu koreksi</small>
                    </div>
                @else
                    {{-- CREATE - Banyak produk --}}
                    <div id="products-wrapper">
                        <div class="product-group mb-3 border p-3 rounded">
                            <label>Produk</label>
                            <select name="products[0][product_id]" class="form-select mb-2" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($product as $productItem)
                                    <option value="{{ $productItem->id }}">{{ $productItem->name }}</option>
                                @endforeach
                            </select>

                            <label>Jumlah Stok</label>
                            <input type="number" name="products[0][quantity]" step="0.01" class="form-control" required>
                        </div>
                    </div>

                    <button type="button" id="add-product" class="btn btn-outline-secondary mb-3">+ Tambah Produk</button>
                @endif

                <button type="submit" class="btn btn-primary">{{ $data->exists ? 'Update' : 'Simpan' }}</button>
                <a href="{{ route('admin.stock') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

@if(!$data->exists)
<script>
    let index = 1;
    document.getElementById('add-product').addEventListener('click', function () {
        const wrapper = document.getElementById('products-wrapper');
        const html = `
            <div class="product-group mb-3 border p-3 rounded">
                <label>Produk</label>
                <select name="products[${index}][product_id]" class="form-select mb-2" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($product as $productItem)
                        <option value="{{ $productItem->id }}">{{ $productItem->name }}</option>
                    @endforeach
                </select>

                <label>Jumlah Stok</label>
                <input type="number" name="products[${index}][quantity]" step="0.01" class="form-control" required>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', html);
        index++;
    });
</script>
@endif
@endsection
