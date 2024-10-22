@extends('layouts.user_type.guest')

@section('title', 'Kaisar Barber Academy')

@section('page-style')
<style>

</style>
@endsection

@section('content')

<main class="main-content">
  <section>
    <div class="page-header min-vh-100">
      <div class="container py-4">
        <div class="row">
          <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
            <div class="card">
              <div class="card-header pb-0 text-left bg-transparent">
                <div class="app-brand justify-content-center d-flex">
                  <img src="{{ asset('assets/img/logos/logo.png') }}" class="w-50" alt="">
                </div>
              </div>
              <div class="card-body">
                <h4 class="font-weight-bolder text-red">Register Talent</h4>
                <form role="form" method="POST" action="{{route('talent.store-register')}}">
                  @csrf
                  @if ($errors->has('message'))
                  <div class="alert alert-danger">
                    <span class="text-white">{{ $errors->first('message') }}</span>
                  </div>
                  @endif
                  <input type="hidden" name="role" value="talent">
                  <div class="mb-3">
                    <label for="user-name" class="form-control-label">Nama Lengkap<span class="text-danger">*</span></label>
                    <div class="">
                      <input class="form-control @error('name') border border-danger @enderror" type="text" value="{{ old('name') }}" placeholder="Nama Lengkap" id="user-name" name="name">
                    </div>
                    @error('name')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="user-tgl_lahir" class="form-control-label">Tanggal Lahir</label>
                    <div class="">
                      <input class="form-control @error('tgl_lahir') border border-danger @enderror" type="date" value="{{ old('tgl_lahir') }}" placeholder="Tanggal Lahir" id="user-tgl_lahir" name="tgl_lahir">
                    </div>
                    @error('tgl_lahir')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="user-email" class="form-control-label">Email<span class="text-danger">*</span></label>
                    <div class="">
                      <input class="form-control @error('email') border border-danger @enderror" type="email" value="{{ old('email') }}" placeholder="@example.com" id="user-email" name="email">
                    </div>
                    @error('email')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="user.phone" class="form-control-label">Nomor HP<span class="text-danger">*</span></label>
                    <div class="">
                      <input class="form-control @error('phone_number') border border-danger @enderror" type="tel" value="{{ old('phone_number') }}" placeholder="08123xxx" id="number" name="phone_number">
                    </div>
                    @error('phone_number')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="user.location" class="form-control-label">Alamat</label>
                    <div class="">
                      <textarea class="form-control @error('address') border border-danger @enderror" placeholder="Alamat" value="{{ old('address') }}" id="name" name="address" value="{{ auth()->user()->location }}"></textarea>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label>Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                    @error('password')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="confirm-new-password" class="form-control-label">Konfirmasi Password<span class="text-danger">*</span></label>
                    <div class="">
                      <input class="form-control" type="password" value="{{ old('password') }}" placeholder="Masukan Password" id="confirm-new-password" name="confirm_new_password">
                    </div>
                    <span id="password-alert" class="text-danger text-xs mt-1" style="display: none;">Password yang dimasukan tidak sama</span>
                  </div>
                  <div class="text-center">
                    <button type="submit" id="submit-button" class="btn bg-red w-100 mt-4 mb-0" disabled>Daftar</button>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center pt-0 px-lg-2 px-1">
                <p class="mb-4 text-sm mx-auto">
                  Sudah memiliki akun?
                  <a href="{{ route('talent.login') }}" class="text-info text-gradient font-weight-bold">Log in</a>
                </p>
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

@section('script')
<script>
  function toTitleCase(str) {
    return str.replace(
      /\w\S*/g,
      function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      }
    );
  }

  document.getElementById('confirm-new-password').addEventListener('input', function() {
    var newPassword = document.getElementById('password').value;
    var confirmNewPassword = this.value;
    var alertText = document.getElementById('password-alert');
    var submitButton = document.getElementById('submit-button');

    if (confirmNewPassword !== newPassword) {
      this.classList.add('border', 'border-danger');
      alertText.style.display = 'block'; // Tampilkan pesan jika password tidak sesuai
      submitButton.disabled = true; // Disable tombol submit
    } else {
      this.classList.remove('border', 'border-danger');
      alertText.style.display = 'none'; // Sembunyikan pesan jika password sesuai
      submitButton.disabled = false; // Enable tombol submit
    }
  });

  document.getElementById('password').addEventListener('input', function() {
    var confirmNewPassword = document.getElementById('confirm-new-password').value;
    var newPassword = this.value;
    var alertText = document.getElementById('password-alert');
    var submitButton = document.getElementById('submit-button');

    if (confirmNewPassword !== newPassword) {
      document.getElementById('confirm-new-password').classList.add('border', 'border-danger');
      alertText.style.display = 'block'; // Tampilkan pesan jika password tidak sesuai
      submitButton.disabled = true; // Disable tombol submit
    } else {
      document.getElementById('confirm-new-password').classList.remove('border', 'border-danger');
      alertText.style.display = 'none'; // Sembunyikan pesan jika password sesuai
      submitButton.disabled = false; // Enable tombol submit
    }
  });
</script>
@endsection