@extends('layouts.master')
@section('title', 'Manage Product')
@section('content')

<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h6>{{ isset($data->id) ? 'Edit Product' : 'Tambah Product' }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($data->id) ? route('admin.product.update', $data->id) : route('admin.product.store') }}"
                method="POST" enctype="multipart/form-data">
              @csrf
              @if(isset($data->id))
                  @method('PUT')
              @endif

              <div class="mb-3">
                  <label for="name">Nama Product</label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                         value="{{ old('name', $data->name ?? '') }}" required>
                  @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                  <label for="price">Price</label>
                  <input type="number" step="0.01" placeholder="Harga Beli" name="price" class="form-control @error('price') is-invalid @enderror"
                         value="{{ old('price', $data->price ?? '') }}" required>
                  @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3">
                  <label for="price_sell">Price Sell</label>
                  <input type="number" step="0.01" placeholder="Harga Jual" name="price_sell" class="form-control @error('price_sell') is-invalid @enderror"
                         value="{{ old('price_sell', $data->price_sell ?? '') }}" required readonly>
                  @error('price_sell') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">

                    <label for="laba">Laba</label>
                    <input type="number" step="0.01" name="laba" id="laba" class="form-control @error('laba') is-invalid @enderror" value="{{ old('laba', $data->laba ?? '') }}" required>
                    @error('laba') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


              <div class="mb-3">
                  <label for="category">Category</label>
                  <select class="form-control" name="category" id="category" required>
                    <option value=""  disabled selected>Pilih Category</option>
                      <option value="titipan" {{ old('category', $data->category ?? '') == 'titipan' ? 'selected' : '' }}>Sayur Titipan</option>
                      <option value="beli" {{ old('category', $data->category ?? '') == 'beli' ? 'selected' : '' }}>Sayur Beli</option>
                  </select>
              </div>

              <div class="mb-3">
                  <label for="id_satuan">Satuan</label>
                  <select class="form-control" name="id_satuan" id="id_satuan" required>
                    <option value="" disabled selected>Pilih Satuan</option>
                      @foreach ($satuan as $satu)
                          <option value="{{ $satu->id }}" {{ old('id_satuan', $data->id_satuan ?? '') == $satu->id ? 'selected' : '' }}>
                              {{ $satu->nama_satuan }}
                          </option>
                      @endforeach
                  </select>
              </div>

              <div class="mb-3">
                  <label for="image">Image Product</label>
                  <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                  @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                  @if (!empty($data->image))
                      <div class="mt-2">
                          <img src="{{ asset('storage/' . $data->image) }}" class="img-thumbnail" width="100">
                          <div class="form-check mt-2">
                              <input type="checkbox" class="form-check-input" name="remove_image" value="1" id="remove_image">
                              <label for="remove_image" class="form-check-label">Hapus gambar</label>
                          </div>
                      </div>
                  @endif
              </div>

              <button type="submit" class="btn btn-success">{{ isset($data->id) ? 'Update' : 'Simpan' }}</button>
          </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const priceInput = document.querySelector('input[name="price"]');
    const labaInput = document.querySelector('input[name="laba"]');
    const priceSellInput = document.querySelector('input[name="price_sell"]');

    function calculatePriceSell() {
        const price = parseFloat(priceInput.value) || 0;
        const laba = parseFloat(labaInput.value) || 0;
        priceSellInput.value = price + laba;
    }

    // Add event listeners
    priceInput.addEventListener('input', calculatePriceSell);
    labaInput.addEventListener('input', calculatePriceSell);

    // Calculate on page load for existing data
    calculatePriceSell();
});
</script>

@endsection
