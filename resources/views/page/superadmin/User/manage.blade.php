@extends('layouts.master')
@section('title', 'Manage User')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h6>{{ $data->id ? 'Edit User' : 'Tambah User' }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ $data->id ? route('admin.user.update', $data->id) : route('admin.user.store') }}" method="POST">
                @csrf
                @if ($data->id)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $data->username) }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password">Password {{ $data->id ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ $data->id ? '' : 'required' }}>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_role">Role</label>
                    <select name="id_role" class="form-control @error('id_role') is-invalid @enderror" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('id_role', $data->id_role) == $role->id ? 'selected' : '' }}>
                                {{ $role->role }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_tossa">Tossa</label>
                    <select name="id_tossa" class="form-control @error('id_tossa') is-invalid @enderror">
                        <option value="">-- Pilih Tossa --</option>
                        @foreach($tossas as $tossa)
                            <option value="{{ $tossa->id }}" {{ old('id_tossa', $data->id_tossa) == $tossa->id ? 'selected' : '' }}>
                                {{ $tossa->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_tossa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_shift">Shift</label>
                    <select name="id_shift" class="form-control @error('id_shift') is-invalid @enderror">
                        <option value="">-- Pilih Shift --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('id_shift', $data->id_shift) == $shift->id ? 'selected' : '' }}>
                                {{ $shift->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_shift')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">{{ $data->id ? 'Update' : 'Simpan' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
