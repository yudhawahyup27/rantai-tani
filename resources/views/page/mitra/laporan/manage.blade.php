@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">

            <h3>{{ $data->exists ? 'Edit' : 'Tambah' }} Laporan Keuangan</h3>

            <form method="POST" action="{{ $data->exists ? route('dashboard.mitra.laporan.update', $data->id) : route('dashboard.mitra.laporan.store') }}">
                @csrf
                @if($data->exists)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="id_user" class="form-label">Pilih Mitra</label>
                    <select name="id_user" class="form-select" required>
                        <option value="">-- Pilih Mitra --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('id_user', $data->id_Pic) == $user->id ? 'selected' : '' }}>
                                {{ $user->username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ravenue" class="form-label">Revenue</label>
                    <input type="number" name="ravenue" id="ravenue" class="form-control" required value="{{ old('ravenue', $data->ravenue) }}">
                </div>

                <div class="mb-3">
                    <label for="pengeluaran" class="form-label">Pengeluaran</label>
                    <input type="number" name="pengeluaran" id="pengeluaran" class="form-control" required value="{{ old('pengeluaran', $data->pengeluaran) }}">
                </div>

                <div class="mb-3">
                    <label for="gajikaryawan" class="form-label">Gaji Karyawan</label>
                    <input type="number" name="gajikaryawan" id="gajikaryawan" class="form-control" required value="{{ old('gajikaryawan', $data->gajikaryawan) }}">
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Catatan</label>
                    <textarea name="note" id="note" class="form-control" rows="3">{{ old('note', $data->note) }}</textarea>
                </div>

                <div class="alert alert-info text-white">
                    <strong>Catatan:</strong> Dagangan Baru, Laba Kotor, dan Laba Bersih dihitung otomatis oleh sistem.
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.laporan.manage') }}" class="btn btn-secondary">Kembali</a>
            </form>

        </div>
    </div>
</div>
@endsection
