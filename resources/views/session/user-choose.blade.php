@extends('layouts/user_type/blank')

@section('title', 'Kaisar Barber Academy')

@section('page-style')
<style>
    .authentication-wrapper {
        display: flex;
        flex-basis: 100%;
        min-height: fit-content;
        width: 100%;
    }

    .h-full {
        height: 100vh;
    }
</style>
@endsection

@section('content')
<div class="container-xxl h-full py-5 d-flex flex-column align-items-center">
    <div class="image-header d-flex mx-auto" style="width: 16rem;">
        <img src="{{ asset('assets/img/logos/logo.png') }}" class="w-100" alt="">
    </div>
    <div class="authentication-wrapper flex-column container-p-y">
        <div class="my-auto">
            <h4 class="text-red text-center">Selamat Datang di <br> Kaisar Barber Academy!</h4>
            <p class="text-center"> Mohon pilih akses anda.</p>
            <div class="col-12 col-lg-8 d-flex mx-auto row g-3 g-lg-5">
                <div class="col-md-4 col-12">
                    <a href="{{ route('trainer.login') }}" class="card flex-row px-3 py-4 border border-red rounded-3 border-1 fw-bold text-center justify-content-center align-items-center">
                        <img src="{{ asset('assets/img/icons/Male.png') }}" class="col-lg-3 col-2" alt="">
                        <h5 class="text-red my-auto ms-4 fw-bold">TRAINER</h5>
                    </a>
                </div>
                <div class="col-md-4 col-12">
                    <a href="{{ route('student.login') }}" class="card flex-row px-3 py-4 border border-red rounded-3 border-1 fw-bold text-center justify-content-center align-items-center">
                        <img src="{{ asset('assets/img/icons/Hairdresser.png') }}" class="col-lg-3 col-2" alt="">
                        <h5 class="text-red my-auto ms-4 fw-bold">STUDENT</h5>
                    </a>
                </div>
                <div class="col-md-4 col-12">
                    <a href="{{ route('talent.login') }}" class="card flex-row px-3 py-4 border border-red bg-glass rounded-3 border-1 fw-bold text-center justify-content-center align-items-center">
                        <img src="{{ asset('assets/img/icons/Haircut.png') }}" class="col-lg-3 col-2" alt="">
                        <h5 class="text-red my-auto ms-4 fw-bold">TALENT</h5>
                    </a>
                </div>
            </div>
        </div>
        <!-- /Register -->
    </div>
</div>
@endsection