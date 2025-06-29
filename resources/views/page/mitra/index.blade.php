@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Data Update Harga</h3>

            <div class="table-responsive mt-4">
                <table class="table ">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="sticky-column bg-white" style="left: 0; z-index: 0;">Product</th>
                            <th>Harga Sebelum</th>
                            <th>Harga Terbaru</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $d)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="sticky-column bg-white" style="left: 0; z-index: 0;">{{ $d->product->name }}</td>
                            <td>Rp {{ number_format($d->old_price_sell, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($d->new_price_sell, 0, ',', '.') }}</td>
                            @if($d->new_price_sell > $d->old_price_sell)
       <td> <span class="badge bg-success">Naik</span></td>
    @elseif($d->new_price_sell < $d->old_price_sell)
       <td> <span class="badge bg-danger">Turun</span></td>
    @else
       <td> <span class="badge bg-secondary">Tetap</span></td>
    @endif
                            <td>{{ $d->created_at->format('d-m-Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                                      <div class="d-flex justify-content-center mt-4">
    <nav aria-label="Pagination">
        <ul class="pagination pagination-sm">
            {{-- Tombol Previous --}}
            @if ($data->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="ni ni-bold-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $data->previousPageUrl() }}" aria-label="Previous">
                        <i class="ni ni-bold-left"></i>
                    </a>
                </li>
            @endif

            {{-- Tombol Nomor Halaman --}}
            @for ($i = 1; $i <= $data->lastPage(); $i++)
                <li class="page-item {{ $data->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Tombol Next --}}
            @if ($data->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $data->nextPageUrl() }}" aria-label="Next">
                        <i class="ni ni-bold-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="ni ni-bold-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
</div>
            </div>

        </div>
    </div>
</div>
@endsection
