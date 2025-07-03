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


            <div class="mb-3 d-none " id="pemilik-wrapper">
            <label for="pemilik">Nama Pemilik Titipan</label>
        <input type="text" name="pemilik" placeholder="Masukan nama pemilik titipan"
           class="form-control @error('pemilik') is-invalid @enderror text-capitalize"
           value="{{ old('pemilik', $data->pemilik ?? '') }}">
    @error('pemilik') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
          <div class="mb-3">
                  <label for="price">Price</label>
                  <input type="number" step="0.01" placeholder="Harga Beli" name="price" class="form-control @error('price') is-invalid @enderror"
                         value="{{ old('price', $data->price ?? '') }}" required>
                  @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
               <div class="mb-3">

                

                    <label for="laba">Laba</label>
                    <input type="number" step="0.01" name="laba" id="laba" class="form-control @error('laba') is-invalid @enderror" value="{{ old('laba', $data->laba ?? '') }}" required>
                    @error('laba') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              <div class="mb-3">
                  <label for="price_sell">Price Sell</label>
                  <input type="number" step="0.01" placeholder="Harga Jual" name="price_sell" class="form-control @error('price_sell') is-invalid @enderror"
                         value="{{ old('price_sell', $data->price_sell ?? '') }}" required readonly>
                  @error('price_sell') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>



                 <label for="harga_rekomendasi">Harga Rekomendasi</label>
    <input type="number" step="0.01" name="harga_rekomendasi"
        class="form-control @error('harga_rekomendasi') is-invalid @enderror"
        value="{{ old('harga_rekomendasi', $data->harga_rekomendasi ?? '') }}">
    @error('harga_rekomendasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

              <div class="mb-3">
                  <label for="category">Category</label>
                  <select class="form-control" name="category" id="category" required>
                    <option value=""  disabled selected>Pilih Category</option>
                      <option value="titipan" {{ old('category', $data->category ?? '') == 'titipan' ? 'selected' : '' }}> Titipan</option>
                      <option value="beli" {{ old('category', $data->category ?? '') == 'beli' ? 'selected' : '' }}> Beli</option>
                  </select>
              </div>
              <div class="mb-3">
                  <label for="jenis">Jenis</label>
                  <select class="form-control" name="jenis" id="jenis" required>
                    <option value=""  disabled selected>Pilih jenis</option>
                      <option value="sayur" {{ old('jenis', $data->jenis ?? '') == 'sayur' ? 'selected' : '' }}>Sayur</option>
                      <option value="buah" {{ old('jenis', $data->jenis ?? '') == 'buah' ? 'selected' : '' }}>Buah</option>
                      <option value="garingan" {{ old('jenis', $data->jenis ?? '') == 'garingan' ? 'selected' : '' }}>Garingan</option>
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


<div class="mb-3">
    <label for="catatan">Catatan</label>
    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3"
        placeholder="Tambahkan catatan jika ada...">{{ old('catatan', $data->catatan ?? '') }}</textarea>
    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

              <div class="mb-3">
                  <label for="image">Image Product</label>
                  <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                  @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                  @if (!empty($data->image))
                      <div class="mt-2">
                          <img src="{{ asset('/storage/app/public/' . $data->image) }}" class="img-thumbnail" width="100">
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
    const categorySelect = document.querySelector('select[name="category"]')
    const pemilik = document.getElementById('pemilik-wrapper')
    const pemilikInput = pemilik.querySelector('input[name="pemilik"]')

    function calculatePriceSell() {
        const price = parseFloat(priceInput.value) || 0;
        const laba = parseFloat(labaInput.value) || 0;
        priceSellInput.value = price + laba;
    }

    const togglePemilik = () =>{
        if (categorySelect.value === 'titipan') {
            pemilik.classList.add('d-block')
            pemilik.classList.remove('d-none')
            pemilikInput.required
        } else {
            pemilik.classList.remove('d-block')
            pemilik.classList.add('d-none')

        }
    }




    // Add event listeners
    categorySelect.addEventListener('change', togglePemilik)
    priceInput.addEventListener('input', calculatePriceSell);
    labaInput.addEventListener('input', calculatePriceSell);

    // Calculate on page load for existing data
    calculatePriceSell();
    togglePemilik();
});
</script>

@endsection
