@extends('layouts.user_type.guest')

@section('title', 'Kaisar Barber Academy')

@section('page-style')
<style>

</style>
@endsection

@section('content')

<main class="main-content  mt-0">
  <section>
    <div class="page-header min-vh-100">
      <div class="container">
        <div class="row">
          <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
            <div class="card">
              <div class="card-header pb-0 text-left bg-transparent">
                <div class="app-brand justify-content-center d-flex">
                  <img src="{{ asset('assets/img/logos/logo.png') }}" class="w-50" alt="">
                </div>
              </div>
              <div class="card-body">
                <h4 class="font-weight-bolder text-red">Login Trainer</h4>
                <p class="mb-0">Mohon untuk login terlebih dahulu<br></p>
                <form role="form" method="POST" action="{{route('student.store-login')}}">
                  @csrf
                  @if ($errors->has('message'))
                  <div class="alert alert-danger">
                    <span class="text-white">{{ $errors->first('message') }}</span>
                  </div>
                  @endif
                  <input type="hidden" name="role" value="trainer">
                  <label>Email</label>
                  <div class="mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                    @error('email')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <label>Password</label>
                  <div class="mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                    @error('password')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="rememberMe" checked="" name="remember" value="1">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-red w-100 mt-4 mb-0">Sign in</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
              <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/curved6.jpg')"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

@endsection