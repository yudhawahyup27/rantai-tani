@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
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
  </div>
</div>
@endsection
