@extends('layouts.master')

@section('title','Stock')

@section('content')

<div class="container">
<div class="row">
    @foreach ( $data as $datas )
    <div class="col-6 col-md-4">
        <a href="{{ route('detail', ['id' => $datas->id]) }}">
            <div class="card rounded shadow p-4 bg-primary text-center fw-bolder">
               <h6 class="text-uppercase text-white">{{ $datas->name }}</h6>
            </div>
        </a>
    </div>
@endforeach
</div>
</div>

@endsection
