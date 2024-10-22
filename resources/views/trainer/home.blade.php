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
</style>
@endsection

@section('content')
<div class="container py-3">
  <div class="row gx-5">
    <div class="col-lg-4 col-md-6">
      <h4>Jadwal Hari Ini</h4>
      <h6 class="text-red">Teori</h6>
      <ul class="list-group">
        @foreach($today_theories as $theory)
        <li class="list-group-item mb-2 rounded-3 border-0 p-3 bg-block">
          <div class="row">
            <div class="col-3 align-self-center">
              <img src="{{ ($theory->course->user->photo_path ? asset('storage/' . $theory->course->user->photo_path) : asset('assets/img/user-1.jpg'))}}"
                class="w-100 border-radius-lg border border-2 border-white"
                style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                alt="">
            </div>
            <div class="col-9 px-0 align-center">
              <h6 class="text-white fw-black mb-0">{{ucwords($theory->course->user->name)}}</h6>
              <span class="badge rounded-pill bg-light text-dark">{{ucwords($theory->course->class->name)}}</span>
            </div>
          </div>
        </li>
        @endforeach

        @if($today_theories->isEmpty())
        <li class="list-group-item border-0 d-flex align-items-center p-0">
          <div class="bg-transparent alert d-flex flex-row align-items-center justify-content-center w-100 text-red mb-0" role="alert">
            <img src="{{ asset('assets/img/icons/Warning.png') }}" width="50" alt="">
            <span class="ms-3">Tidak ada Teori hari ini</span>
          </div>
        </li>
        @endif
      </ul>
      <h6 class="text-red">Praktek</h6>
      <ul class="list-group mb-4">
        @foreach($today_practices as $practice)
        <li class="list-group-item mb-2 rounded-3 border-0 p-3 bg-block">
          <div class="row">
            <div class="col-2">
              <img src="{{ ($practice->course->user->photo_path ? asset('storage/' . $practice->course->user->photo_path) : asset('assets/img/user-1.jpg'))}}"
                class="w-100 border-radius-lg border border-2 border-white"
                style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                alt="">
            </div>
            <div class="col-10 px-0">
              <h6 class="text-white fw-black mb-0">{{ucwords($practice->course->user->name)}}</h6>
              <span class="text-white text-sm">{{ucfirst($practice->session_time)}} ({{$practice->session_time === 'siang' ? '14:00 - 17.00' : '18:30 - 21:00'}})</span>
              @if($practice->talents->isEmpty())
              <div><span class="badge badge-sm bg-gradient-danger">Belum ada talent</span></div>
              @else
              <div class="mt-2">
                @foreach($practice->talents as $talent)
                <span class="badge badge-sm bg-gradient-success mb-1">
                  {{ $talent->name }} ({{ 'Sesi ' . $talent->pivot->session_time }})
                </span>
                @endforeach
              </div>
              @endif
            </div>
          </div>
        </li>
        @endforeach

        @if($today_practices->isEmpty())
        <li class="list-group-item border-0 d-flex align-items-center p-0">
          <div class="bg-transparent alert  d-flex flex-row align-items-center justify-content-center w-100 text-red mb-0" role="alert">
            <img src="{{ asset('assets/img/icons/Warning.png') }}" width="50" alt="">
            <span class="ms-3">Tidak ada praktek hari ini</span>
          </div>
        </li>
        @endif
      </ul>
    </div>
    <div class="col-lg-4 col-md-6">
      <h4>Jadwal Besok</h4>
      <h6 class="text-red">Teori</h6>
      <ul class="list-group">
        @foreach($tomorrow_theories as $theory)
        <li class="list-group-item mb-2 rounded-3 border-0 p-3 bg-block">
          <div class="row">
            <div class="col-3 align-self-center">
              <img src="{{ ($theory->course->user->photo_path ? asset('storage/' . $theory->course->user->photo_path) : asset('assets/img/user-1.jpg'))}}"
                class="w-100 border-radius-lg border border-2 border-white"
                style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                alt="">
            </div>
            <div class="col-9 px-0 align-center">
              <h6 class="text-white fw-black mb-0">{{ucwords($theory->course->user->name)}}</h6>
              <span class="badge rounded-pill bg-light text-dark">{{ucwords($theory->course->class->name)}}</span>
            </div>
          </div>
        </li>
        @endforeach

        @if($tomorrow_theories->isEmpty())
        <li class="list-group-item border-0 d-flex align-items-center p-0 mb-2">
          <div class="bg-transparent alert  d-flex flex-row align-items-center justify-content-center w-100 text-red mb-0" role="alert">
            <img src="{{ asset('assets/img/icons/Warning.png') }}" width="50" alt="">
            <span class="ms-3">Tidak ada teori besok</span>
          </div>
        </li>
        @endif
      </ul>
      <h6 class="text-red">Praktek</h6>
      <ul class="list-group bg-transparent mb-4">
        @foreach($tomorrow_practices as $practice)
        <li class="list-group-item mb-2 rounded-3 border-0 p-3 bg-block">
          <div class="row">
            <div class="col-2">
              <img src="{{ ($practice->course->user->photo_path ? asset('storage/' . $practice->course->user->photo_path) : asset('assets/img/user-1.jpg'))}}"
                class="w-100 border-radius-lg border border-2 border-white"
                style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                alt="">
            </div>
            <div class="col-10 px-0">
              <h6 class="text-white fw-black mb-0">{{ucwords($practice->course->user->name)}}</h6>
              <span class="text-white text-sm">{{ucfirst($practice->session_time)}} ({{$practice->session_time === 'siang' ? '14:00 - 17.00' : '18:30 - 21:00'}})</span>
              @if($practice->talents->isEmpty())
              <div><span class="badge badge-sm bg-gradient-danger">Belum ada talent</span></div>
              @else
              <div class="mt-2">
                @foreach($practice->talents as $talent)
                <span class="badge badge-sm bg-gradient-success mb-1">
                  {{ $talent->name }} ({{ 'Sesi ' . $talent->pivot->session_time }})
                </span>
                @endforeach
              </div>
              @endif
            </div>
          </div>
        </li>
        @endforeach

        @if($today_practices->isEmpty())
        <li class="list-group-item border-0 d-flex align-items-center p-0">
          <div class="bg-transparent alert d-flex flex-row align-items-center justify-content-center w-100 text-red mb-0" role="alert">
            <img src="{{ asset('assets/img/icons/Warning.png') }}" width="50" alt="">
            <span class="ms-3">Tidak ada praktek besok</span>
          </div>
        </li>
        @endif
      </ul>
    </div>
    <div class="col-lg-4 col-md-6">
      <h4>Cari Jadwal</h4>
      <form action="" id="search-schedule-form">
        <div class="row mb-2">
          <div class="col-8"><input class="form-control" type="date" name="date" id="date-input"></div>
          <div class="col-4"><button class="btn btn-primary w-100 mb-0" type="button" id="search-button">Cari</button></div>
        </div>
      </form>
      <div id="cari-jadwal" hidden>
        <h6 class="text-red">Teori</h6>
        <ul class="list-group" id="theory-list">
        </ul>

        <h6 class="text-red">Praktek</h6>
        <ul class="list-group" id="practice-list">
        </ul>
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

  $(document).ready(function() {
    $('#search-button').click(function() {
      var date = $('#date-input').val();

      if (!date) {
        alert('Silakan pilih tanggal.');
        return;
      }

      $.ajax({
        url: "{{ route('search.schedule') }}",
        method: 'GET',
        data: {
          date: date
        },
        success: function(response) {
          $('#theory-list').empty();
          $('#practice-list').empty();

          $('#cari-jadwal').removeAttr('hidden');

          // Tangani data teori
          if (response.theories.length > 0) {
            response.theories.forEach(function(theory) {
              $('#theory-list').append(`
                                <li class="list-group-item my-2 rounded-3 border-0 p-3 bg-block">
                                    <div class="row">
                                        <div class="col-3 align-self-center">
                                            <img src="${theory.photo_path ? '{{ asset("storage/") }}/' + theory.photo_path : '{{ asset("assets/img/user-1.jpg") }}'}" class="w-100 border-radius-lg border border-2 border-white" alt="">
                                        </div>
                                        <div class="col-9 px-0 align-center">
                                            <h6 class="text-white fw-black mb-0">${theory.name}</h6>
                                            <span class="badge rounded-pill bg-light text-dark">${theory.class_name}</span>
                                        </div>
                                    </div>
                                </li>
                            `);
            });
          } else {
            $('#theory-list').append(`
            <li class="list-group-item border-0 d-flex align-items-center p-0">
              <div class="bg-transparent alert d-flex flex-row align-items-center justify-content-center w-100 text-red mb-0" role="alert">
                <img src="{{ asset('assets/img/icons/Warning.png') }}" width="50" alt="">
                <span class="ms-3">Tidak ada teori</span>
              </div>
            </li>
            `);
          }

          // Tangani data praktek dan talents
          if (response.practices.length > 0) {
            response.practices.forEach(function(practice) {
              var talentsHtml = '';
              if (practice.talents.length > 0) {
                practice.talents.forEach(function(talent) {
                  talentsHtml += `
                                        <span class="badge badge-sm bg-gradient-success mb-1">
                                            ${talent.name} (Sesi ${talent.session_time})
                                        </span>
                                    `;
                });
              } else {
                talentsHtml = `<div><span class="badge badge-sm bg-gradient-danger">Belum ada talent</span></div>`;
              }

              $('#practice-list').append(`
                                <li class="list-group-item my-2 rounded-3 border-0 p-3 bg-block">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="${practice.photo_path ? '{{ asset("storage/") }}/' + practice.photo_path : '{{ asset("assets/img/user-1.jpg") }}'}" 
                                              class="w-100 border-radius-lg border border-2 border-white"
                                              style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                                              alt="">
                                        </div>
                                        <div class="col-9 px-0">
                                            <h6 class="text-white fw-black mb-0">${toTitleCase(practice.name)}</h6>
                                            <span class="text-white text-sm">${toTitleCase(practice.session_time)} (${practice.session_time === 'siang' ? '14:00 - 17.00' : '18:30 - 21:00'})</span>
                                            <div class="mt-2">
                                                ${talentsHtml}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            `);
            });
          } else {
            $('#practice-list').append(`
            <li class="list-group-item border-0 d-flex align-items-center p-0">
              <div class="bg-transparent alert d-flex flex-row align-items-center justify-content-center w-100 text-red mb-0" role="alert">
                <img src="{{ asset('assets/img/icons/Warning.png') }}" width="50" alt="">
                <span class="ms-3">Tidak ada teori</span>
              </div>
            </li>
            `);
          }
        },
        error: function() {
          alert('Terjadi kesalahan, silakan coba lagi.');
        }
      });
    });
  });
</script>
@endsection