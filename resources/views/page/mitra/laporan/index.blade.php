@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Data Laporan</h3>
    <a href="{{ route('mitra.laporan.manage') }}" class="btn btn-primary">Create Laporan</a>
            <div class="table-responsive mt-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Dagangan Baru</th>
                            <th>Revenue</th>
                            <th>Margin</th>
                            <th>Laba Dibawa</th>
                            <th>Pengeluaran</th>
                            <th>Sewa Tossa</th>
                            <th>Gaji Karyawan</th>
                            <th>Dagangan Laku Terjual</th>
                            <th>Range Laba Kotor</th>
                            <th>Laba Kotor</th>
                            <th>Gross Margin (%)</th>
                            <th>Laba Bersih</th>
                            <th>PIC</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $d)
                        <tr>
                            <td>{{ $d->created_at->format('d-m-Y') }}</td>
                            <td>{{ number_format($d->daganganBaru, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->ravenue, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->margin, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->labaDibawa, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->pengeluaran, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->sewaTossa, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->gajikaryawan, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->daganganlakuterjual, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->rangelabakotor, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->labakotor, 0, ',', '.') }}</td>
                            <td>{{ number_format($d->grosMargin, 2) }}%</td>
                            <td>{{ number_format($d->labaBersih, 0, ',', '.') }}</td>
                            <td>{{ $d->pic->username ?? '-' }}</td>
                            <td>{{ $d->note }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="15" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
