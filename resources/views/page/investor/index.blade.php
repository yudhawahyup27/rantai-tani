@extends('layouts.master')

@section('title', 'Dashboard Investor')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Porto</p>
                <h5 class="font-weight-bolder mt-2">
                  Rp {{ number_format($totalPorto, 0, ',', '.') }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-center my-2">
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Deviden perBulan</p>
                <h5 class="font-weight-bolder mt-2">
                  Rp {{ number_format($totaldeviden, 0, ',', '.') }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-center my-2">
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Lot</p>
                <h5 class="font-weight-bolder mt-2">
                  {{ $totalLot }} Lot
                </h5>
              </div>
            </div>
            <div class="col-4 text-center my-2">
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add more cards here -->
  </div>
  <a href="/dashboard/investor/detail-saham" class="btn-primary w-100 mt-2 btn">Detail Saham</a>
  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6>Distribusi Jumlah Uang per Perusahaan</h6>
        </div>
        <div class="card-body">
          <canvas id="moneyChart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6>Distribusi Jumlah Lot per Perusahaan</h6>
        </div>
        <div class="card-body">
          <canvas id="lotChart"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Get data from PHP and convert for chart use
  const companyLabels = {!! json_encode(array_keys($companyData)) !!};
  const companyMoney = {!! json_encode(array_column($companyData, 'money')) !!};
  const companyLots = {!! json_encode(array_column($companyData, 'lots')) !!};

  // Donut Chart - Jumlah uang per perusahaan
  const ctxMoney = document.getElementById('moneyChart').getContext('2d');
  new Chart(ctxMoney, {
    type: 'doughnut',
    data: {
      labels: companyLabels,
      datasets: [{
        label: 'Jumlah Uang (Rp)',
        data: companyMoney,
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#20c9a6'],
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
          }
        }
      }
    }
  });

  // Pie Chart - Jumlah lot per perusahaan
  const ctxLot = document.getElementById('lotChart').getContext('2d');
  new Chart(ctxLot, {
    type: 'pie',
    data: {
      labels: companyLabels,
      datasets: [{
        label: 'Jumlah Lot',
        data: companyLots,
        backgroundColor: ['#e74a3b', '#858796', '#20c9a6', '#36b9cc', '#4e73df', '#1cc88a', '#f6c23e'],
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              return value + ' Lot';
            }
          }
        }
      }
    }
  });
</script>
@endsection
