@extends('layouts.master')

@section('title', 'Take Over Saham')
@section('content')
<div class="container">
    <div class="card p-4">
        <h4>{{ $data->id ? 'Edit' : 'Tambah' }} Takeover</h4>

        <form action="{{ $data->id ? route('admin.takeover.update', $data->id) : route('admin.takeover.store') }}" method="POST">
            @csrf
            @if($data->id)
                @method('PUT')
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mb-3">
                <label>Investor</label>
                <select name="investor_id" id="investorSelect" class="form-control" required>
                    <option value="">-- Pilih Investor --</option>
                    @foreach($investors as $inv)
                        <option value="{{ $inv->id }}" data-lot="{{ $inv->perlot }}" {{ old('investor_id', $data->investor_id) == $inv->id ? 'selected' : '' }}>
                            {{ $inv->user->username }} - {{ $inv->tossa ? $inv->tossa->name : '-' }} (Lot: {{ $inv->perlot }})
                        </option>
                    @endforeach
                </select>
                <small id="lotInfo" class="text-muted mt-1 d-block"></small>
            </div>

            <div class="mb-3">
                <label>User Tujuan</label>
                <select name="to_user_id" class="form-control" required>
                    <option value="">-- Pilih User Tujuan --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('to_user_id', $data->to_user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->username }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah Lot</label>
                <input type="number" name="perlot" class="form-control" value="{{ old('perlot', $data->perlot) }}" required>
                <small class="text-muted">Jumlah lot tidak boleh melebihi jumlah lot investor</small>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.takeover') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<script>
    // Jalankan saat halaman dimuat untuk menampilkan info lot jika sudah ada investor yang dipilih
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('investorSelect');
        if (select.selectedIndex > 0) {
            updateLotInfo(select);
        }
    });

    document.getElementById('investorSelect').addEventListener('change', function() {
        updateLotInfo(this);
    });

    function updateLotInfo(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const lot = selectedOption.getAttribute('data-lot') || 0;
        document.getElementById('lotInfo').textContent = 'Investor memiliki ' + lot + ' lot';
    }
</script>
@endsection
