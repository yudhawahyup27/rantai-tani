@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <h3>Data Omset</h3>

            {{-- Filter Form --}}
            <form action="{{ route('omset.index') }}" method="GET" class="mb-3 d-flex gap-2 align-items-center">
                <select name="filter" class="form-select" style="width: 150px;" onchange="toggleRangeInputs(this.value)">
                    <option value="hari" {{ $filter == 'hari' ? 'selected' : '' }}>Hari</option>
                    <option value="minggu" {{ $filter == 'minggu' ? 'selected' : '' }}>Minggu</option>
                    <option value="bulan" {{ $filter == 'bulan' ? 'selected' : '' }}>Bulan</option>
                    <option value="range" {{ $filter == 'range' ? 'selected' : '' }}>Range</option>
                </select>

                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" style="display: none;">
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" style="display: none;">
                <button class="btn btn-primary" type="submit">Filter</button>
            </form>

            {{-- Chart --}}
            <canvas id="shiftChart" height="100"></canvas>

            {{-- Table --}}
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Shift</th>
                        <th>Produk</th>
                        <th>Stok Awal</th>
                        <th>Stock Awal Akumulasi</th>
                        <th>Barang Terjual</th>
                        <th>Terjual Akumulasi</th>
                        <th>Stok Akhir</th>
                        <th>Stock Terakhir Akumulasi</th>
                        <th>Omset (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenues as $rev)
                    <tr>
                        <td>{{ $rev->date }}</td>
                        <td>{{ ucfirst($rev->shift) }}</td>
                        <td>{{ $rev->product->name ?? '-' }}</td>
                        <td>{{ $rev->stock_start }}</td>
                        <td>{{ $rev->start_value }}</td>
                        <td>{{ $rev->sold_quantity }}</td>
                        <td>{{ $rev->stock_value }}</td>
                        <td>{{ $rev->stock_end }}</td>
                        <td>{{ $rev->end_value }}</td>
                        <td>{{ number_format($rev->revenue, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
function toggleRangeInputs(value) {
    const start = document.getElementById('start_date');
    const end = document.getElementById('end_date');
    if (value === 'range') {
        start.style.display = 'inline-block';
        end.style.display = 'inline-block';
    } else {
        start.style.display = 'none';
        end.style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('shiftChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!}, // ['Pagi', 'Sore']
            datasets: [{
                label: 'Total Omset per Shift (Rp)',
                data: {!! json_encode($chartData) !!}, // [total pagi, total sore]
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: { display: true }
            }
        }
    });
});
</script>
@endsection
