@extends('layouts.master')
@section('title', 'Daftar Supply Network')
@section('content')
@php
    use Illuminate\Support\Str;
@endphp
<div class="container-fluid py-4">
    <div class="card mb-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                <span class="alert-text"> {{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between">
            <h6>Daftar Products</h6>
            <div class="d-flex flex-column flex-md-row">
                <form method="GET" action="{{ route('admin.product') }}" class="mb-2 me-2">
                    <div class="d-flex">
                        <input style="width: 200px;" type="text" name="search" class="form-control me-2" placeholder="Cari..." value="{{ request('search') }}">
                        <select name="perpage" class="form-select me-2" onchange="this.form.submit()">
                            <option value="5" {{ request('perpage') == 5 ? 'selected' : '' }}>5</option>
                            <option value="25" {{ request('perpage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="100" {{ request('perpage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('admin.product.manage') }}" class="btn btn-primary">Tambah Product</a>
            </div>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="beli-tab" data-bs-toggle="tab" href="#beli" role="tab">Produk Beli</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="titip-tab" data-bs-toggle="tab" href="#titip" role="tab">Produk Titipan</a>
                </li>
            </ul>
            <div class="tab-content pt-3" id="productTabsContent">
                <div class="tab-pane fade show active" id="beli" role="tabpanel">
                    @php $groupedBuy = $productsBuy->groupBy('category'); @endphp
                    @foreach ($groupedBuy as $category => $products)
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0 text-uppercase">{{ $category ?? 'Tanpa Kategori' }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th class="sticky-column" style="left: 0; z-index: 0;">Name</th>
                                                <th>Image</th>
                                                <th>Price Buy</th>
                                                <th>Price Sell</th>
                                                <th>Category</th>
                                                <th>Satuan</th>
                                                <th>Laba</th>
                                                <th>Di Update</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($products as $sn)
                                                <tr class="text-center">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="sticky-column bg-white" style="left: 0; z-index: 0;">{{ $sn->name }}</td>
                                                    <td>
                                                        @if($sn->image)
                                                            <img src="{{ asset('/storage/app/public/' . $sn->image) }}" alt="{{ $sn->name }}" width="100">
                                                        @else
                                                            <span class="text-muted">No Image</span>
                                                        @endif
                                                    </td>
                                                    <td>Rp. {{ number_format($sn->price, 0, ',', '.') }}</td>
                                                    <td>Rp. {{ number_format($sn->price_sell, 0, ',', '.') }}</td>
                                                    <td>{{ $sn->category ?? '-' }}</td>
                                                    <td>{{ $sn->satuan->nama_satuan ?? '-' }}</td>
                                                    <td>Rp. {{ number_format($sn->laba, 0, ',', '.') }}</td>
                                                    <td>{{ $sn->updated_at ? $sn->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <a href="{{ route('admin.product.manage', $sn->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                            <form action="{{ route('admin.product.delete', $sn->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="10" class="text-center">Tidak ada data</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-center mt-3">
                        {{ $productsBuy->appends(request()->query())->fragment('beli')->links() }}
                    </div>
                </div>

                <div class="tab-pane fade" id="titip" role="tabpanel">
                    @php $groupedTitip = $productsTitip->groupBy('category'); @endphp
                    @foreach ($groupedTitip as $category => $products)
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0 text-uppercase">{{ $category ?? 'Tanpa Kategori' }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th class="sticky-column" style="left: 0; z-index: 0;">Name</th>
                                                <th>Image</th>
                                                <th>Price Buy</th>
                                                <th>Price Sell</th>
                                                <th>Category</th>
                                                <th>Satuan</th>
                                                <th>Laba</th>
                                                <th>Di Update</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($products as $sn)
                                                <tr class="text-center">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="sticky-column bg-white" style="left: 0; z-index: 0;">{{ $sn->name }}</td>
                                                    <td>
                                                        @if($sn->image)
                                                            <img src="{{ asset('/storage/app/public/' . $sn->image) }}" alt="{{ $sn->name }}" width="100">
                                                        @else
                                                            <span class="text-muted">No Image</span>
                                                        @endif
                                                    </td>
                                                    <td>Rp. {{ number_format($sn->price, 0, ',', '.') }}</td>
                                                    <td>Rp. {{ number_format($sn->price_sell, 0, ',', '.') }}</td>
                                                    <td>{{ $sn->category ?? '-' }}</td>
                                                    <td>{{ $sn->satuan->nama_satuan ?? '-' }}</td>
                                                    <td>Rp. {{ number_format($sn->laba, 0, ',', '.') }}</td>
                                                    <td>{{ $sn->updated_at ? $sn->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <a href="{{ route('admin.product.manage', $sn->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                            <form action="{{ route('admin.product.delete', $sn->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="10" class="text-center">Tidak ada data</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-center mt-3">
                        {{ $productsTitip->appends(request()->query())->fragment('titip')->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
