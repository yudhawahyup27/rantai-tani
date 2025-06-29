@extends('layouts.master')
@section('title', 'Daftar Investor')

@section('content')

<div class="container py-4">
    <div class="card p-4">

        <form action="{{ $data->id ? route('admin.investor.update', $data->id) : route('admin.investor.create') }}" method="POST">
            @csrf
            @if ($data->id)
                @method('PUT')
            @endif

            <!-- Nama Investor -->
            <div class="mb-3">
                <label for="name">Nama Investor</label>
                <select name="name" class="form-control @error('name') is-invalid @enderror" required>
                    <option value="">-- Pilih Investor --</option>
                    @foreach($user as $id => $username)
                        <option value="{{ $id }}" {{ old('name', $data->user_id) == $id ? 'selected' : '' }}>
                            {{ $username }}
                        </option>
                    @endforeach
                </select>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Pilih Tossa -->
            <div class="mb-3">
                <label for="tossa">Tossa</label>
                <select name="tossa" id="tossa" class="form-control @error('tossa') is-invalid @enderror" required>
                    <option value="">-- Pilih Tossa --</option>
                    @foreach($tossa as $id => $name)
                        <option value="{{ $id }}" {{ old('tossa_id', $data->tossa_id) == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('tossa')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Input Jumlah Lot -->
            <div class="mb-3">
                <label for="lot">Jumlah Lot</label>
                <input type="number" id="lot" name="lot" class="form-control @error('lot') is-invalid @enderror" value="{{ old('lot', $data->perlot) }}" required min="1">
                @error('lot')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Persentase Dividen -->
            <div class="mb-3">
                <label for="persentase">Persentase Dividen</label>
                <input type="text" id="persentase" name="persentase" class="form-control" value="{{ old('persentase', '0%') }}" readonly>
            </div>

            <!-- Nominal Dividen /Bulan -->
            <div class="mb-3">
                <label for="Deviden_display">Nominal Dividen /Bulan</label>
                <input type="text" id="Deviden_display" class="form-control" readonly>
                <input type="hidden" id="Deviden" name="Deviden" value="{{ old('Deviden', $data->Deviden) }}">
                @error('Deviden')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ $data->id ? 'Update' : 'Simpan' }}</button>
        </form>

    </div>
</div>

<!-- Script Perhitungan -->
<script>
    const tossaData = @json($tossaDetail); // Ex: { 1: { harga: 1000000, persentase: 2.5 }, ... }

    const tossaSelect = document.getElementById('tossa');
    const lotInput = document.getElementById('lot');
    const nominalInput = document.getElementById('Deviden');
    const nominalDisplay = document.getElementById('Deviden_display');
    const persentaseInput = document.getElementById('persentase');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        }).format(angka);
    }

    function updateNominalDividen() {
        const tossaId = tossaSelect.value;
        const lot = parseFloat(lotInput.value) || 0;

        const data = tossaData[tossaId];
        if (!data) {
            nominalDisplay.value = '';
            nominalInput.value = '';
            persentaseInput.value = '';
            return;
        }

        const harga = parseFloat(data.harga);
        const persentase = parseFloat(data.persentase);

        const totalHarga = harga * lot;
        const nominal = (totalHarga * (persentase / 100)) / 12;

        nominalDisplay.value = formatRupiah(nominal);
        nominalInput.value = nominal;
        persentaseInput.value = persentase + '%';
    }

    tossaSelect.addEventListener('change', updateNominalDividen);
    lotInput.addEventListener('input', updateNominalDividen);
    window.addEventListener('DOMContentLoaded', updateNominalDividen);
</script>

@endsection
