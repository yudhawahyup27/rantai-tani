@extends('layouts.master')
@section('title', 'Manage Shift')
@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h6>{{ $data->id ? 'Edit Shift' : 'Tambah Shift' }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ $data->id ? route('admin.shift.update', $data->id) : route('admin.shift.store') }}" method="POST">
                @csrf
                @if ($data->id)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name">Shift</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="start_time">Shift Start Time</label>
                    <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $data->start_time) }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="end_time">Shift End Time</label>
                    <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $data->end_time) }}" required>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">{{ $data->id ? 'Update' : 'Simpan' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
