@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">


    <h3>{{ $data->exists ? 'Edit' : 'Tambah' }} Laporan</h3>

    <form method="POST" action="{{ $data->exists ? route('admin.laporan.update', $data->id) : route('admin.laporan.store') }}">
        @csrf
        @if($data->exists)
            @method('PUT')
        @endif

       <div class="mb-3">
      <label for="id_user" class="form-label">Pilih Mitra</label>
      <select name="id_user" class="form-select" required>
        <option value="">-- Pilih Mitra --</option>
        @foreach ($users as $user)
          <option value="{{ $user->id }}" {{ old('id_user', $data->id_user) == $user->id ? 'selected' : '' }}>
            {{ $user->username }}
          </option>
        @endforeach
      </select>
    </div>

        <div class="mb-3">
            <label for="bonus" class="form-label">Bonus</label>
            <input type="number" step="0.01" name="bonus" id="bonus" class="form-control" required value="{{ old('bonus', $data->bonus ?? 0) }}">
        </div>

        <div class="alert alert-info text-white">
            <strong>Catatan:</strong> Laba Sayur, Buah, Garingan, Passive Income, dan Total akan dihitung otomatis dari data omset hari ini.
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
      </div>
    </div>
</div>

@endsection
