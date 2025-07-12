@extends('layouts.master')

@section('title', $data->exists ? 'Edit Stok' : 'Tambah Stok')

@section('content')
<div class="container mt-4">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>{{ $data->exists ? 'Edit Stok Produk' : 'Tambah Banyak Stok' }}</h3>
        </div>
        <div class="card-body">
            <form  onsubmit="return confirm('Apakah Anda yakin menyimpan data ini?')" action="{{ $data->exists ? route('admin.stock.update', $data->id) : route('admin.stock.store') }}" method="POST">
                @csrf
                @if($data->exists)
                    @method('PUT')
                @endif

                {{-- Supply Network --}}
                <div class="mb-3">
                    <label for="tossa_id" class="form-label">Supply Network</label>
                    <select name="tossa_id" class="form-select select2" required {{ $data->exists ? 'disabled' : '' }}>
                        <option value="">-- Pilih Network --</option>
                        @foreach ($tossa as $t)
                            <option value="{{ $t->id }}"
                                {{ old('tossa_id', $data->tossa_id) == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($data->exists)
                        <input type="hidden" name="tossa_id" value="{{ $data->tossa_id }}">
                    @endif
                </div>

                {{-- Form Create (Multiple Products) --}}
                @if(!$data->exists)
                    <div id="product-container">
                        <div class="row mb-3 product-row">
                            <div class="col-md-6">
                                <label>Produk</label>
                                <select name="products[0][product_id]" class="form-select select2" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($product as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Jumlah</label>
                                <input type="text" name="products[0][quantity]" class="form-control" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-row">Hapus</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mb-3" id="add-product">+ Tambah Produk</button>
                @else
                    {{-- Form Edit --}}
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk</label>
                        <select name="product_id" class="form-select select2" required disabled>
                            @foreach ($product as $p)
                                <option value="{{ $p->id }}" {{ $p->id == $data->product_id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="product_id" value="{{ $data->product_id }}">
                    </div>

                    <div class="mb-3">
                        <label for="quantity_new" class="form-label">Jumlah Stok Baru</label>
                        <input type="text" name="quantity_new" class="form-control" value="{{ old('quantity_new', $data->quantity_new) }}">
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah Total Stok</label>
                        <input type="text" name="quantity" class="form-control" value="{{ old('quantity', $data->quantity) }}">
                        <small class="text-danger">*Isi jika ingin mengoreksi jumlah stok total</small>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">{{ $data->exists ? 'Update' : 'Simpan' }}</button>
                <a href="{{ route('admin.stock') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

{{-- Select2 JS + jQuery --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- JS untuk dynamic input dan Select2 --}}
@if (!$data->exists)
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Select2 awal
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    let index = 1;

    document.getElementById('add-product').addEventListener('click', function () {
        const container = document.getElementById('product-container');
        const newRow = document.querySelector('.product-row').cloneNode(true);

        // Reset value dan ganti index name
        newRow.querySelectorAll('select, input').forEach(function (el) {
            el.name = el.name.replace(/\[\d+\]/, `[${index}]`);
            el.value = '';
        });

        // Hapus Select2 lama dan re-init
        $(newRow).find('select.select2').select2('destroy');

        container.appendChild(newRow);

        // Init Select2 ulang setelah append
        $(newRow).find('select.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        index++;
    });

    document.getElementById('product-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            if (document.querySelectorAll('.product-row').length > 1) {
                e.target.closest('.product-row').remove();
            }
        }
    });
});
</script>
@endif
@endsection
