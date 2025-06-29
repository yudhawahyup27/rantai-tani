@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
  {{-- <div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's Money</p>
                <h5 class="font-weight-bolder">
                  $53,000
                </h5>
                <p class="mb-0">
                  <span class="text-success text-sm font-weight-bolder">+55%</span>
                  since yesterday
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Add more cards here -->
  </div> --}}

  <div class="card my-4">
      <div class="card-body">
<h4>Total Passive Income: Rp {{ number_format($totalHargaSewa, 0, ',', '.') }}</h4>

              <form method="GET" class="mb-4">
              <div class="row g-3 align-items-end">
                  <div class="col-md-4">
                      <label for="from" class="form-label">Dari Tanggal:</label>
                      <input id="from" class="form-control" type="date" name="from" value="{{ $from }}">
                  </div>
                  <div class="col-md-4">
                      <label for="to" class="form-label">Sampai Tanggal:</label>
                      <input id="to" class="form-control" type="date" name="to" value="{{ $to }}">
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                      <button class="btn btn-primary w-100" type="submit">Filter</button>
                  </div>
              </div>

              </form>
          <div class="row">
            <div class="col-6 my-4">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive ">
                        <table class="table  table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama Tossa</th>
                                    <th>Harga Sewa</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sewas as $index => $sewa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ optional($sewa->user)->username ?? 'User Tidak Diketahui' }}</td>
                                        <td>{{ optional($sewa->user->userTossa)->name  ?? 'Tossa Tidak Diketahui' }}</td>
                                        <td>Rp {{ number_format($sewa->hargaSewa, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($sewa->created_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-6 my-4">
                <div class="card ">
                  <div class="card-body">
                      <div class="row mt-4">
                  <div class="col-md-6">
                      <canvas id="barChart"  height="250"></canvas>
                  </div>
                  <div class="col-md-6">
                      <canvas id="donutChart"  height="120"></canvas>
                  </div>
              </div>
                  </div>
                </div>
            </div>
        </div>
        </div>
  </div>


     <div class="card my-4">
        <div class="card-header">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="date" class="form-label">Filter Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control"
                        value="{{ $selectedDate }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if (!empty($data) && count($data))
                @foreach ($data as $group)
                    <div class="mb-4">
                        <h5 class="mb-1">{{ $group['date_formatted'] }} - {{ $group['pemilik'] }}</h5>
                        <p>Total Revenue: <strong>Rp {{ number_format($group['total_revenue'], 0, ',', '.') }}</strong></p>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah Terjual</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group['products'] as $product)
                                    <tr>
                                        <td>{{ $product['product_name'] }}</td>
                                        <td>{{ $product['sold_quantity'] }}</td>
                                        <td>Rp {{ number_format($product['revenue'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning">
                    Data tidak ditemukan untuk tanggal tersebut.
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);

    const labels = chartData.map(item => item.label);
    const totals = chartData.map(item => item.total);

    // BAR CHART
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Passive Income',
                data: totals,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Bar Chart: Passive Income per Tossa (User)'
                }
            }
        }
    });

    // DONUT CHART
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Passive Income',
                data: totals,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Donut Chart: Persentase Passive Income'
                }
            }
        }
    });
</script>
@endsection
