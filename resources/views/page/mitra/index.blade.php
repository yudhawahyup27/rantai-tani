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
                            <th class="sticky-column" style="left: 0; z-index: 2;">Product</th>
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
                            <td class="sticky-column" style="left: 0; z-index: 2;">{{ $d->product->name }}</td>
                            <td>Rp {{ $d->old_price_sell }}</td>
                            <td>Rp {{ $d->new_price_sell }}</td>
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
                            <td colspan="4" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
