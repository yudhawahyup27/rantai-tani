@extends('layouts.master')
@section('title', 'Daftar Supply Network')
@section('content')
<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between">
            <h6>Daftar Supply Network</h6>
            <div class="d-flex flex-column flex-md-row">
                <form method="GET" action="{{ route('admin.supply') }}" class="mb-2 me-2">
                    <div class="d-flex">

                        <input style="width: 200px;" type="text" name="search" class="form-control me-2" placeholder="Cari..." value="{{ request('search') }}">
                        <select name="per_page" class="form-select me-2" onchange="this.form.submit()">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('admin.supply.manage') }}" class="btn btn-primary">Tambah Supply Network</a>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-like-2"></i> </span>
                <span class="alert-text text-white"> {{ session('success') }}</span>
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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Supply Network</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dibuat</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Di Update</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                </tr>
              </thead>
              <tbody>
              @if ($data->isEmpty())
                <tr>
                    <td colspan="9">
                        <td colspan="9" class="text-center">Data tidak ditemukan</td>
                    </td>
                </tr>
              @else
              @foreach ($data as $sn)
              <tr>
                  <td class="text-center"><h6 class="mb-0 text-sm">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</h6></td>
                  <td class="text-center"><h6 class="mb-0 text-sm">{{ $sn->name }}</h6></td>
                  <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $sn->created_at ?? '-' }}</span></td>
                  <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ $sn->updated_at ?? '-' }}</span></td>
                  <td class="d-flex justify-content-center gap-2">
                      <a href="{{ route('admin.supply.manage', $sn->id) }}" class="btn btn-sm btn-warning">Edit</a>
                      <form action="{{ route('admin.supply.delete', $sn->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                      </form>
                  </td>
              </tr>
          @endforeach
              @endif
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
