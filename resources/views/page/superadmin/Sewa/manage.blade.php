@extends('layouts.master')

@section('title', 'Sewa Manage')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                {{ isset($data->id) ? 'Edit' : 'Tambah' }} Sewa
            </h5>
        </div>
        <div class="card-body">
            <form
                action="{{ isset($data->id) ? route('admin.sewa.update', $data->id) : route('admin.sewa.store') }}"
                method="post"
            >
                @csrf
                @if (isset($data->id))
                    @method('PUT')
                @endif

                <!-- Select User -->
                <div class="mb-3">
                    <label for="user_id" class="form-label">User (Mitra)</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="" disabled {{ !isset($data->user_id) ? 'selected' : '' }}>Pilih user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ (isset($data->user_id) && $data->user_id == $user->id) ? 'selected' : '' }}>
                                {{ $user->username }} @if($user->userTossa) - {{ $user->userTossa->name }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>


               <!-- Harga Sewa -->
<div class="mb-3">
    <label for="hargaSewa" class="form-label">Harga Sewa</label>
    <select name="hargaSewa" id="hargaSewa" class="form-select" required>
        <option value="" disabled {{ !isset($data->hargaSewa) ? 'selected' : '' }}>Pilih harga sewa</option>
        @foreach ([15000, 25000, 50000, 100000] as $harga)
            <option value="{{ $harga }}" {{ (old('hargaSewa', $data->hargaSewa ?? '') == $harga) ? 'selected' : '' }}>
                Rp {{ number_format($harga, 0, ',', '.') }}
            </option>
        @endforeach
    </select>
</div>


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($data->id) ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('admin.sewa.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
