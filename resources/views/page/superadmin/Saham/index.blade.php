@extends('layouts.master')
@section('title', 'Data Saham')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6>Data Master Saham</h6>
            <a href="{{ route('admin.master-saham.manage') }}" class="btn btn-primary">Tambah Saham</a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Supply Network</th>
                            <th>Total Lot</th>
                            <th>Jumlah Lot (TerJual)</th>
                            <th>Jumlah Lot (Tersedia)</th>
                            <th>Persetanse</th>
                            <th>Harga perLot</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->tossa->name ?? '-' }}</td>
                                <td>{{ $row->totallot  }}</td>
                                <td>{{ $row->sahamterjual }}</td>
                                <td>{{ $row->sahamtersedia }}</td>
                                <td>{{ $row->persentase}} % </td>
                                <td>Rp. {{ number_format($row->harga, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($row->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.master-saham.manage', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.master-saham.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if($data->isEmpty())
                            <tr><td colspan="10">Belum ada data</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
