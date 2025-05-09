@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<section>
    <div class="page-header min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                    <div class="card card-plain">
                        <div class="card-header pb-0 text-start">
                            <h4 class="font-weight-bolder">Sign In</h4>
                            <p class="mb-0">Enter your credentials to sign in</p>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger text-white">{{ session('error') }}</div>
                            @endif
                            <form method="POST" action="{{ url('/') }}">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg" name="username" placeholder="Username" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg btn-primary w-100 mt-4">Sign in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                    <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('https://dinkes.pekalongankota.go.id/upload/halaman/halaman_20190416090614.jpg'); background-size: cover;">
                        <span class="mask bg-gradient-primary opacity-6"></span>
                        <h4 class="mt-5 text-white font-weight-bolder position-relative">Rantai Tani</h4>
                        <p class="text-white position-relative">Belanja sayur buah kini lebih cepat dan mudah.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
