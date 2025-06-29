@extends('layouts.master')

@section('title', 'Stock')

@section('content')

<div class="container">
    {{-- Menampilkan pesan error validasi umum dari Laravel --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-icon"><i class="ni ni-fat-remove"></i></span> {{-- Icon untuk error --}}
            <span class="alert-text">

                    @foreach ($errors->all() as $error)
                        <span class="text-white">{{ $error }}</span>
                    @endforeach

            </span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Menampilkan pesan sukses dari session --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-icon"><i class="ni ni-like-2"></i></span> {{-- Icon untuk sukses --}}
            <span class="alert-text"> {{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center"> {{-- Menambahkan d-flex untuk rata kanan kiri --}}
            <div class="card-title fw-bold fs-4">List Stock Produk</div>
            {{-- Tambahkan tombol untuk menambah stok baru jika diperlukan --}}
            {{-- <a href="{{ route('admin.stock.create') }}" class="btn btn-primary">Tambah Stok Baru</a> --}}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Gambar</th>
                            <th>Harga (Produk)</th> {{-- Jelaskan harga ini harga produk, bukan stok --}}
                            <th>Stok Tersedia</th> {{-- Lebih deskriptif dari "Stock" --}}
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $datas) {{-- Gunakan @forelse untuk handle data kosong --}}
                            <tr>
                                <td>{{ $datas->product->name ?? '-' }}</td>
                                <td>
                                    @if ($datas->product && $datas->product->image)
                                        <img src="{{ asset('/storage/app/public/' . $datas->product->image) }}" alt="Gambar Produk" width="60" class="img-thumbnail">
                                    @else
                                        Tidak Ada Gambar
                                    @endif
                                </td>
                                <td>{{ number_format($datas->product->price ?? 0, 0, ',', '.') }}</td> {{-- Format harga --}}
                                <td>{{ $datas->quantity }}</td>
                                <td>{{ $datas->product->category ?? '-' }}</td>
                                <td>{{  $datas->product->satuan->nama_satuan ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.stock.manage', $datas->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.stock.destroy', $datas->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus stok ini? Data yang dihapus tidak dapat dikembalikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                    <a href="{{ route('admin.stock.newStockHistory', $datas->id) }}" class="btn btn-info">
    Lihat Riwayat Penambahan Stok
</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data stok yang tersedia.</td> {{-- colspan sesuai jumlah kolom --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
