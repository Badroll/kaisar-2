@extends('layouts/user_type/universal')

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

  .bg-block {
    background: linear-gradient(92deg, #6666D4 8.6%, #B94648 96.18%);
  }

  .border-white {
    border-color: white;
  }

  @media (min-width: 768px) {
    .bg-md-transparent {
      background-color: transparent !important;
    }
  }
</style>
@endsection

@section('content')
<div class="container">

  <div class="row py-4">
    <div class="col-md-6">
      <!-- Identitas -->
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <div class="row justify-content-between">
            <div class="d-flex flex-column">
              <h6 class="mb-0 text-red">Ganti Password</h6>
            </div>
          </div>
        </div>
        <div class="card-body p-3">
          @php
          $userRole = Request::segment(1);
          @endphp
          <p class="text-danger text-xs mt-2">Password terdiri dari minimal 5 karakter</p>
          <form action="{{ route( $userRole . '.simpan-password') }}" method="POST" role="form text-left">
            @csrf
            @method('PUT')
            @if($errors->any())
            <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
              <span class="alert-text text-white">
                {{$errors->first()}}</span>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
              </button>
            </div>
            @endif
            @if(session('success'))
            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
              <span class="alert-text text-white">
                {{ session('success') }}</span>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
              </button>
            </div>
            @endif
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="old-password" class="form-control-label">Masukan Password Lama<span class="text-danger">*</span></label>
                  <div class="">
                    <input class="form-control @error('old_password') border border-danger @enderror" type="password" value="{{ old('old_password') }}" placeholder="Password Lama" id="old-password" name="old_password">
                  </div>
                  @error('old_password')
                  <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="new-password" class="form-control-label">Masukan Password Baru<span class="text-danger">*</span></label>
                  <div class="">
                    <input class="form-control @error('new_password') border border-danger @enderror" type="password" value="{{ old('new_password') }}" placeholder="Password Baru" id="new-password" name="new_password">
                  </div>
                  @error('new_password')
                  <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="confirm-new-password" class="form-control-label">Konfirmasi Password Baru<span class="text-danger">*</span></label>
                  <div class="">
                    <input class="form-control" type="password" value="" placeholder="Masukan Lagi Password Baru" id="confirm-new-password" name="confirm_new_password">
                  </div>
                  <span id="password-alert" class="text-danger text-xs mt-1" style="display: none;">Password yang dimasukan tidak sama</span>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4" id="submit-button" disabled>Ubah</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
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
    var newPassword = document.getElementById('new-password').value;
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

  document.getElementById('new-password').addEventListener('input', function() {
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