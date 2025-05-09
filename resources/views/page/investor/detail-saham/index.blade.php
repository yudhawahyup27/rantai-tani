@extends('layouts.master')

@section('title','Detail List Saham')
@section('content')
<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between">
            <h6>Daftar Detail Portofolio Saham</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                <span class="alert-text"> {{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr class="text-center">
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Saham</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lot</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Portofolio</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deviden</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jual</th>
                </tr>
              </thead>
              <tbody>

                @if($investments->isEmpty())
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data portofolio.</td>
                </tr>
                @else
                    @foreach($investments as $data)
                    @php
                    $message = "Halo Admin, saya ingin menjual saham {$data->tossaName()} sebanyak {$data->perlot} lot dengan total Rp " . number_format($data->total, 0, ',', '.');
                    $whatsappUrl = 'https://wa.me/6281234567890?text=' . urlencode($message);
                  @endphp

                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $data->tossaName() }}</td>
                        <td class="text-center">{{ $data->perlot }}</td>
                        <td class="text-center">Rp. {{ number_format($data->total, 0, ',', '.') }}</td>
                        <td class="text-center">Rp. {{ number_format($data->Deviden, 0, ',', '.') }}</td>
                        <td class="text-center"> <a href="{{ $whatsappUrl }}" class="btn btn-success btn-sm" target="_blank">
                            Jual via WA
                          </a></td>
                    </tr>
                    @endforeach
                @endif

              </tbody>
            </table>
          </div>
        </div>
      </div>
</div>
@endsection
