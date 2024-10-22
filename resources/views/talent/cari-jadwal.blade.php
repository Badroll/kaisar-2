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
        <h5 class="text-red mb-3">Potong Rambut Tersedia</h5>
        <ul class="list-group">
          @if($practices_available->isEmpty())
          <li class="list-group-item border-0 d-flex align-items-center p-0 mb-3">
            <div class="bg-blue-100 mb-0 alert border border-blue d-flex flex-column align-items-center justify-content-center w-100 text-blue" role="alert">
              <img src="{{ asset('assets/img/icons/Sad.png') }}" width="50" height="50" alt="">
              <span class="fw-bold">Belum ada potong rambut yang tersedia</span>
            </div>
          </li>
          @endif
          @foreach($practices_available as $practice)
          <li class="list-group-item rounded-3 mb-2 border-0 d-flex flex-column p-3 bg-gradient-primary">
            <h6 class="text-teori mb-0">
              <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($practice->session_date)->translatedFormat('l, d/m/Y') }}
            </h6>
            <div class="d-block mt-2">
              <div>
                <div class="d-flex between align-items-center w-100 mb-0">
                  @if($practice->session_time === 'siang')
                  <p class="text-red text-sm fw-bold mb-0 bg-white rounded-pill d-inline py-1 px-3"><i class="text-warning fas fa-sun rounded-1 bg-white"></i> 14.00 - 17.00</p>
                  @else
                  <p class="text-white text-sm fw-bold mb-0 bg-dark rounded-pill d-inline py-1 px-3"><i class="fas fa-moon rounded-1 bg-dark"></i> 18.30 - 21.30</p>
                  @endif
                </div>
                <hr class="border w-full border-white mt-3 mb-2">
                <p class="text-white mb-0 lh-lg">
                  Sesi 1 {{ $practice->session_time === 'siang' ? '14.00 - 15.00' : '18.30 - 19.30' }}
                  <span>
                    @if(is_null($practice->talent_1))
                    <span class="badge bg-gradient-info">Tersedia</span>
                    @else
                    <span class="badge bg-danger">Sudah Terisi</span>
                    @endif
                  </span>
                </p>
                <p class="text-white mb-0 lh-lg">
                  Sesi 2 {{ $practice->session_time === 'siang' ? '15.00 - 16.00' : '19.30 - 20.30' }}
                  <span>
                    @if(is_null($practice->talent_2))
                    <span class="badge bg-gradient-info">Tersedia</span>
                    @else
                    <span class="badge bg-danger">Sudah Terisi</span>
                    @endif
                  </span>
                </p>
                <p class="text-white mb-0 lh-lg">
                  Sesi 3 {{ $practice->session_time === 'siang' ? '16.00 - 17.00' : '20.30 - 21.30' }}
                  <span>
                    @if(is_null($practice->talent_3))
                    <span class="badge bg-gradient-info">Tersedia</span>
                    @else
                    <span class="badge bg-danger">Sudah Terisi</span>
                    @endif
                  </span>
                </p>
                <hr class="border w-full border-white mt-3 mb-2">
                <div class="d-flex justify-content-end">
                  <a href="{{route('talent.pilih-jadwal', $practice->id)}}" class="btn btn-light mb-0">Pilih</a>
                </div>
              </div>
            </div>
          </li>
          @endforeach
        </ul>
        {{ $practices_available->onEachSide(3)->links() }}
      </div>
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