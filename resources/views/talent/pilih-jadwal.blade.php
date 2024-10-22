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

  .bg-sky {
    background-color: #deeaff;
  }

  .bg-btn {
    background-color: #FBE6FF;
  }

  .text-kursus {
    color: #FFEBDF;
  }

  .text-teori {
    font-weight: bold;
    color: #ffe08a;
  }

  .border-white {
    border-color: white;
  }
</style>
@endsection

@section('content')
<div class="container py-3">
  <div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card p-4">
        <div class="card-header p-0">
          <div class="row justify-content-between">
            <div class="col-md-8 d-flex align-items-center">
              <h6 class="mb-0 text-red">Informasi Praktek</h6>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <ul class="list-group">
            <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">Tanggal</strong></li>
            <li class="list-group-item border-0 ps-0 py-0">{{ \Carbon\Carbon::parse($practice->session_date)->translatedFormat('l, d/m/Y') }}</li>
            <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">Sesi</strong></li>
            <li class="list-group-item border-0 ps-0 py-0">{{ ucwords($practice->session_time) }}</li>
            <li class="list-group-item border-0 ps-0 pb-0 text-sm"><strong class="text-dark">Pukul</strong></li>
            @if($practice->session_time == "siang")
            <li class="list-group-item border-0 ps-0 py-0">14.00 - 17.00</li>
            @else
            <li class="list-group-item border-0 ps-0 py-0">18.30 - 21.30</li>
            @endif
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card p-4">
        <form action="{{route('talent.store-jadwal', $practice->id)}}" method="POST" role="form text-left">
          @csrf
          @if($errors->any())
          <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
            <span class="alert-text text-white">
              {{$errors->first()}}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
              <i class="fa fa-close" aria-hidden="true"></i>
            </button>
          </div>
          @endif
          <div class="form-group">
            <label for="session_time" class="form-control-label">Pilih Sesi<span class="text-danger">*</span></label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="session_time" id="sesi1" value="talent_1" {{ is_null($practice->talent_1) ? '' : 'disabled' }}>
              <label class="form-check-label" for="sesi1" id="sesi1-label">Sesi 1 {{ $practice->session_time == 'siang' ? '14.00 - 15.00' : '18.30 - 19.30' }} {{ is_null($practice->talent_1) ? '(Tersedia)' : '(Terisi)' }}</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="session_time" id="sesi2" value="talent_2" {{ is_null($practice->talent_2) ? '' : 'disabled' }}>
              <label class="form-check-label" for="sesi2" id="sesi2-label">Sesi 2 {{ $practice->session_time == 'siang' ? '15.00 - 16.00' : '19.30 - 20.30' }} {{ is_null($practice->talent_2) ? '(Tersedia)' : '(Terisi)' }}</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="session_time" id="sesi2" value="talent_3" {{ is_null($practice->talent_3) ? '' : 'disabled' }}>
              <label class="form-check-label" for="sesi2" id="sesi2-label">Sesi 3 {{ $practice->session_time == 'siang' ? '16.00 - 17.00' : '20.30 - 21.30' }} {{ is_null($practice->talent_3) ? '(Tersedia)' : '(Terisi)' }}</label>
            </div>
          </div>
          <span class="text-danger text-sm">Perhatian: <br> Jadwal yang sudah dipilih tidak dapat diubah, jika terdapat kesalahan mohon hubungi Admin</span>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Pilih</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @endsection

  @section('script')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
    function toTitleCase(str) {
      return str.replace(
        /\w\S*/g,
        function(txt) {
          return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }
      );
    }

    document.addEventListener("DOMContentLoaded", function() {

    });
  </script>
  @endsection