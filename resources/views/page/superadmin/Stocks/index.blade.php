@extends('layouts.master')

@section('title','Stock')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
              <a class="btn btn-primary" href="{{ route('admin.stock.manage') }}">Tambah Product</a>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ( $data as $datas )
                <div class="col-6 col-md-4 my-4 gap-4">
                    <a class="my-4" href="{{ route('admin.detail.stock', ['id' => $datas->id]) }}">
                        <div class="card rounded shadow p-4 bg-primary text-center fw-bolder">
                           <h6 class="text-uppercase text-white">{{ $datas->name }}</h6>
                        </div>
                    </a>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
