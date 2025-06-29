@extends('layouts.master')

@section('title', $data->exists ? 'Edit Stok' : 'Tambah Stok')

@section('content')

<div class="container mt-4">
 @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-icon"><i class="ni ni-fat-remove"></i></span> {{-- Icon untuk error --}}
            <span class="alert-text">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                    <h2>{{ $data->exists ? 'Edit Data Stok' : 'Tambah Data Stok' }}</h2>
            </div>
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
                @foreach ($product as $product)
                    <option value="{{ $product->id }}" {{ $product->id == $data->product_id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tossa_id" class="form-label">Pilih Supply Network</label>
            <select name="tossa_id" class="form-select" required>
                <option value="">-- Pilih Network --</option>
                @foreach ($tossa as $product)
                    <option value="{{ $product->id }}" {{ $product->id == $data->tossa_id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>


        {{-- TAMPILKAN HANYA SAAT CREATE --}}
        @if ($data->exists)
          <div class="mb-3">
            <label for="quantity_new" class="form-label">Jumlah Stok Baru</label>
            <input type="number" name="quantity_new" class="form-control" value="{{ old('quantity_new', $data->quantity_new) }}" required>
        </div>

        @endif
        <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah Stok</label>
            <input type="text" name="quantity" class="form-control" value="{{ old('quantity', $data->quantity) }}" required>
            @if ($data->exists)
            <small class="text-danger">*Hanya dirubah Jika Hanya Ada Kesalahan jumlah stock</small>
            @endif
        </div>


        <button type="submit" class="btn btn-primary">
            {{ $data->exists ? 'Update' : 'Simpan' }}
        </button>

        <a href="{{ route('admin.stock') }}" class="btn btn-secondary">Kembali</a>
    </form>
        </div>
    </div>



</div>
@endsection
