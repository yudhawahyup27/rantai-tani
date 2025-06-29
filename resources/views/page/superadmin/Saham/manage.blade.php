@extends('layouts.master')
@section('title', 'Manage Product')
@section('content')

<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h6>{{ isset($data->id) ? 'Edit Saham' : 'Tambah Saham' }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($data->id) ? route('admin.master-saham.update', $data->id) : route('admin.master-saham.store') }}"
                method="POST" enctype="multipart/form-data">
              @csrf
              @if(isset($data->id))
                  @method('PUT')
              @endif


              <div class="mb-3">
                <label for="tossa_id">Supply Chain</label>
                <select class="form-control @error('tossa_id') is-invalid @enderror" name="tossa_id" id="tossa_id" required>
                    <option value="" disabled selected>Pilih Supply Chain</option>
                    @foreach ($tossa as $satu)
                        <option value="{{ $satu->id }}" {{ old('tossa_id', $data->tossa_id ?? '') == $satu->id ? 'selected' : '' }}>
                            {{ $satu->name }}
                        </option>
                    @endforeach
                </select>
                @error('tossa_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="totallot">Lot Saham</label>
                <input type="number" name="totallot" class="form-control @error('totallot') is-invalid @enderror"
                       value="{{ old('totallot', $data->totallot ?? '') }}" required>
                @error('totallot')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="persentase">Persentase /Tahun </label>
                <input type="number" name="persentase" class="form-control @error('persentase') is-invalid @enderror"
                       value="{{ old('persentase', $data->persentase ?? '') }}" required>
                @error('persentase')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="harga">Harga perLot</label>
                <input type="number" step="0.01" placeholder="Harga per lembar saham" name="harga"
                       class="form-control @error('harga') is-invalid @enderror"
                       value="{{ old('harga', $data->harga ?? '') }}" required>
                @error('harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

              <button type="submit" class="btn btn-success">{{ isset($data->id) ? 'Update' : 'Simpan' }}</button>
          </form>

        </div>
    </div>
</div>

@endsection
