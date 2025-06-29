@extends('layouts.master')
@section('title','List Beli Saham')
@section('content')
<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between">
            <h6>Daftar Portofolio Saham</h6>
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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lot Tersedia</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga Perlot</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deviden Yang Didapat</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">beli</th>
                </tr>
              </thead>
              <tbody>

                @if($data->isEmpty())
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data Saham.</td>
                </tr>
                @else
                    @foreach($data as $data)
                    @php
                    $message = "Halo Admin, saya ingin membeli saham {$data->tossa->name} sebanyak {} lot ";
                    $whatsappUrl = 'https://wa.me/' . $nomerhp->telepon . '?text=' . urlencode($message);
                  @endphp

                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $data->tossa->name }}</td>
                        <td class="text-center">{{ $data->sahamtersedia }}</td>
                        <td class="text-center">Rp. {{ number_format($data->harga, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $data->persentase }} % </td>
                        <td class="text-center">
                            @if ($data->sahamtersedia == 0)
                                <button class="btn btn-success btn-sm" disabled>Beli via WA</button>
                            @else
                                <a href="{{ $whatsappUrl }}" class="btn btn-success btn-sm" target="_blank">
                                    Beli via WA
                                </a>
                            @endif
                        </td>
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
