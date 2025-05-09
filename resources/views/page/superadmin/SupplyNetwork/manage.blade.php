@extends('layouts.master')
@section('title', 'Manage Supply Network')
@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h6>{{ $data->id ? 'Edit Supply Network' : 'Tambah Supply Network' }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ $data->id ? route('admin.supply.update', $data->id) : route('admin.supply.store') }}" method="POST">
                @csrf
                @if ($data->id)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name">Supply Network</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">{{ $data->id ? 'Update' : 'Simpan' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
