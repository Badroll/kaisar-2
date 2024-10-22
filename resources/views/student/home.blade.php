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
  <div class="d-inline-flex flex-row p-4 mb-4 card">
    <img src="{{ ($user->photo_path ? asset('storage/' . $user->photo_path) : asset('assets/img/user-1.jpg'))}}"
      class="rounded-circle border border-3 border-white"
      style="object-fit: cover; width: 72px; height: 72px;"
      alt="">
    <div class="col ms-3 align-self-center">
      <p class="mb-0 lh-1 text-sm">Halo,</p>
      <h6 class="mb-1 lh-sm text-red">{{ucwords($user->name)}} 👋</h6>
      <span class="badge badge-sm bg-gradient-secondary">Student</span>
    </div>
  </div>
  <div class="row gx-5">
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Kursus Aktif</h4>
        <a href="{{ route('student.kursus') }}" id="editButton" class="text-sm align-middle">
          Lihat Semua
        </a>
      </div>
      <ul class="list-group">
        @foreach($courses as $course)
        <li class="list-group-item rounded-3 mb-2 border-0 d-flex flex-column p-3 bg-gradient-primary">
          <div class="d-flex justify-between align-items-center w-100">
            <h6 class="text-kursus mb-0">{{$course->class->name}}</h6>
            @if($course->status == 'created')
            <span class="badge badge-sm ms-auto bg-white text-secondary">
              <i class="fas fa-pencil-alt"></i> {{$course->status}}
            </span>
            @elseif($course->status == 'scheduled')
            <span class="badge badge-sm ms-auto bg-white text-warning">
              <i class="fas fa-calendar-alt"></i> {{$course->status}}
            </span>
            @elseif($course->status == 'completed')
            <span class="badge badge-sm ms-auto bg-white text-success">
              <i class="fas fa-check-circle"></i> {{$course->status}}
            </span>
            @elseif($course->status == 'canceled')
            <span class="badge badge-sm ms-auto bg-white text-danger">
              <i class="far fa-times-circle"></i> {{$course->status}}
            </span>
            @endif
          </div>

          <div class="d-inline-flex">
            <div>
              <p class="text-light text-sm mb-0">Dari</p>
              <p class="text-white text-sm fw-bold">{{ \Carbon\Carbon::parse($course->start_date)->format('d/m/Y') }}</p>
            </div>
            <div class="ms-3">
              <p class="text-light text-sm mb-0">Sampai</p>
              <p class="text-white text-sm fw-bold">{{ \Carbon\Carbon::parse($course->end_date)->format('d/m/Y') }}</p>
            </div>
          </div>

          <div class="d-flex mt-0 justify-content-end">
            <a href="{{route('student.detail-kursus', $course->id)}}" class="rounded-pill btn bg-btn text-primary py-2 px-3 mb-0">
              <i class="fas fa-eye"></i> Detail
            </a>
            @if($course->jumlah_teori < 1)
              <button class="rounded-pill btn bg-btn text-red py-2 px-3 mb-0 ms-2"
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#teoriModal"
              id="buatTeori-{{$course->id}}"
              data-course-id="{{ $course->id }}"
              data-start-date="{{ $course->start_date }}"
              data-end-date="{{ $course->end_date }}">
              <i class="fas fa-calendar-plus"></i> Jadwalkan Teori
              </button>
              @elseif($course->sisa_praktek > 0)
              <a href="{{route('student.jadwal-praktek', $course->id)}}" class="rounded-pill btn bg-btn text-primary py-2 px-3 mb-0 ms-2">
                <i class="fas fa-calendar-plus"></i> Jadwalkan Praktek
              </a>
              @endif
          </div>
        </li>
        @endforeach

        @if($courses->isEmpty())
        <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
          <div class="bg-red-100 alert border border-red d-flex flex-column align-items-center justify-content-center w-100 text-red" role="alert">
            <img src="{{ asset('assets/img/icons/Warning.png') }}" alt="">
            <span>Belum ada kursus yang diambil!</span>
          </div>
        </li>
        @endif
      </ul>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
      <h4 class="mb-3">Kegiatan yang Akan Datang</h4>
      <ul class="list-group">
        @if($theories_soon->isEmpty() && $practices_soon->isEmpty())
        <li class="list-group-item border-0 d-flex align-items-center p-0 mb-2">
          <div class="bg-blue-100 mb-0 alert border border-blue d-flex flex-column align-items-center justify-content-center w-100 text-blue" role="alert">
            <img src="{{ asset('assets/img/icons/Sad.png') }}" width="50" height="50" alt="">
            <span class="fw-bold">Tidak Ada Kegiatan yang Akan Datang</span>
          </div>
        </li>
        @endif
        @foreach($theories_soon as $theory)
        <li class="list-group-item rounded-3 mb-2 border-0 d-flex flex-column p-3 bg-gradient-primary">
          <div class="d-flex justify-between align-items-center w-100 mb-2">
            <h6 class="text-teori font-sm mb-0">SESI TEORI <i class="fas fa-chalkboard-teacher"></i></h6>
            @if($theory->status == 'created')
            <span class="badge badge-sm ms-auto bg-white text-secondary">
              <i class="fas fa-pencil-alt"></i> {{$theory->status}}
            </span>
            @elseif($theory->status == 'scheduled')
            <span class="badge badge-sm ms-auto bg-white text-warning">
              <i class="fas fa-calendar-alt"></i> {{$theory->status}}
            </span>
            @elseif($theory->status == 'completed')
            <span class="badge badge-sm ms-auto bg-white text-success">
              <i class="fas fa-check-circle"></i> {{$theory->status}}
            </span>
            @elseif($theory->status == 'canceled')
            <span class="badge badge-sm ms-auto bg-white text-danger">
              <i class="far fa-times-circle"></i> {{$theory->status}}
            </span>
            @endif
          </div>
          <div class="d-inline-flex">
            <div>
              <h5 class="text-kursus mb-0">
                {{ \Carbon\Carbon::parse($theory->session_date)->translatedFormat('l, d/m/Y') }}
              </h5>
              <p class="text-light text-sm mb-0">Pukul 09:00 WIB</p>
            </div>
          </div>
        </li>
        @endforeach
        @php
        $number = count($practices_history);
        $number++;
        @endphp
        @foreach($practices_soon as $practice)
        <li class="list-group-item rounded-3 mb-2 border-0 d-flex flex-column p-3 bg-gradient-primary">
          <div class="d-flex justify-between align-items-center w-100 mb-2">
            <h6 class="text-teori font-sm mb-0">PRAKTEK KE-{{ $number }} <i class="fas fa-cut"></i></h6>
            @if($practice->status == 'created')
            <span class="badge badge-sm ms-auto bg-white text-secondary">
              <i class="fas fa-pencil-alt"></i> {{$practice->status}}
            </span>
            @elseif($practice->status == 'scheduled')
            <span class="badge badge-sm ms-auto bg-white text-warning">
              <i class="fas fa-calendar-alt"></i> {{$practice->status}}
            </span>
            @elseif($practice->status == 'completed')
            <span class="badge badge-sm ms-auto bg-white text-success">
              <i class="fas fa-check-circle"></i> {{$practice->status}}
            </span>
            @elseif($practice->status == 'canceled')
            <span class="badge badge-sm ms-auto bg-white text-danger">
              <i class="far fa-times-circle"></i> {{$practice->status}}
            </span>
            @endif
          </div>
          <div class="d-inline-flex">
            <div>
              <h5 class="text-kursus mb-0">
                {{ \Carbon\Carbon::parse($practice->session_date)->translatedFormat('l, d/m/Y') }}
              </h5>
              @if($practice->session_time === 'siang')
              <p class="text-light text-sm mb-0">Pukul 14:00 - 17:00 WIB</p>
              @else
              <p class="text-light text-sm mb-0">Pukul 18:30 - 21:30 WIB</p>
              @endif
            </div>
          </div>
        </li>
        @php
        $number++;
        @endphp
        @endforeach
      </ul>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
      <h4 class="mb-3">History</h4>
      <ul class="list-group">
        @if($theories_history->isEmpty() && $practices_history->isEmpty())
        <li class="list-group-item border-0 d-flex align-items-center p-0 mb-2">
          <div class="bg-blue-100 mb-0 alert border border-blue d-flex flex-column align-items-center justify-content-center w-100 text-blue" role="alert">
            <img src="{{ asset('assets/img/icons/Sad.png') }}" width="50" height="50" alt="">
            <span class="fw-bold">Belum Ada Kegiatan yang Selesai</span>
          </div>
        </li>
        @endif
        @foreach($theories_history as $theory)
        <li class="list-group-item rounded-3 mb-2 border-0 d-flex flex-column p-3 bg-gradient-primary">
          <div class="d-flex justify-between align-items-center w-100 mb-2">
            <h6 class="text-teori font-sm mb-0">SESI TEORI <i class="fas fa-chalkboard-teacher"></i></h6>
            @if($theory->status == 'created')
            <span class="badge badge-sm ms-auto bg-white text-secondary">
              <i class="fas fa-pencil-alt"></i> {{$theory->status}}
            </span>
            @elseif($theory->status == 'scheduled')
            <span class="badge badge-sm ms-auto bg-white text-warning">
              <i class="fas fa-calendar-alt"></i> {{$theory->status}}
            </span>
            @elseif($theory->status == 'completed')
            <span class="badge badge-sm ms-auto bg-white text-success">
              <i class="fas fa-check-circle"></i> {{$theory->status}}
            </span>
            @elseif($theory->status == 'canceled')
            <span class="badge badge-sm ms-auto bg-white text-danger">
              <i class="far fa-times-circle"></i> {{$theory->status}}
            </span>
            @endif
          </div>
          <div class="d-inline-flex">
            <div>
              <h5 class="text-kursus mb-0">
                {{ \Carbon\Carbon::parse($theory->session_date)->translatedFormat('l, d/m/Y') }}
              </h5>
              <p class="text-light text-sm mb-0">Pukul 09:00 WIB</p>
            </div>
          </div>
        </li>
        @endforeach
        @php
        $number = 1;
        @endphp
        @foreach($practices_history as $practice)
        <li class="list-group-item rounded-3 mb-2 border-0 d-flex flex-column p-3 bg-gradient-primary">
          <div class="d-flex justify-between align-items-center w-100 mb-2">
            <h6 class="text-teori font-sm mb-0">PRAKTEK KE-{{ $number }} <i class="fas fa-cut"></i></h6>
            @if($practice->status == 'created')
            <span class="badge badge-sm ms-auto bg-white text-secondary">
              <i class="fas fa-pencil-alt"></i> {{$practice->status}}
            </span>
            @elseif($practice->status == 'scheduled')
            <span class="badge badge-sm ms-auto bg-white text-warning">
              <i class="fas fa-calendar-alt"></i> {{$practice->status}}
            </span>
            @elseif($practice->status == 'completed')
            <span class="badge badge-sm ms-auto bg-white text-success">
              <i class="fas fa-check-circle"></i> {{$practice->status}}
            </span>
            @elseif($practice->status == 'canceled')
            <span class="badge badge-sm ms-auto bg-white text-danger">
              <i class="far fa-times-circle"></i> {{$practice->status}}
            </span>
            @endif
          </div>
          <div class="d-inline-flex">
            <div>
              <h5 class="text-kursus mb-0">
                {{ \Carbon\Carbon::parse($practice->session_date)->translatedFormat('l, d/m/Y') }}
              </h5>
              @if($practice->session_time === 'siang')
              <p class="text-light text-sm mb-0">Pukul 14:00 - 17:00 WIB</p>
              @else
              <p class="text-light text-sm mb-0">Pukul 18:30 - 21:30 WIB</p>
              @endif
            </div>
          </div>
        </li>
        @php
        $number++;
        @endphp
        @endforeach
      </ul>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="teoriModal" tabindex="-1" role="dialog" aria-labelledby="teoriModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="teoriModalLabel">Jadwalkan Teori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('student.store-teori') }}" method="POST" role="form text-left" enctype="multipart/form-data">
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
            <div class="col-md-12" hidden>
              <div class="form-group">
                <label for="course_id" class="form-control-label">Pilih Kursus Siswa<span class="text-danger">*</span></label>
                <input type="hidden" id="course_id" name="course_id" value="">
                @error('course_id')
                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                @enderror
              </div>
            </div>
            <div class="col-md-12" hidden>
              <div class="form-group">
                <label for="status" class="form-control-label">Status<span class="text-danger">*</span></label>
                <select class="form-control" id="status" name="status">
                  <option value="scheduled" selected>Scheduled</option>
                  <option value="completed">Completed</option>
                  <option value="canceled">Canceled</option>
                </select>
                @error('status')
                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-9">
              <div class="form-group">
                <label for="session_date" class="form-control-label">Tanggal Teori<span class="text-danger">*</span></label>
                <input class="form-control @error('session_date') border border-danger @enderror" type="date" id="session_date" name="session_date" value="{{ old('session_date') }}">
                @error('session_date')
                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn bg-gradient-primary">Simpan</button>
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

    var teoriModal = document.getElementById('teoriModal');

    teoriModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var courseId = button.getAttribute('data-course-id');
      var inputCourseId = teoriModal.querySelector('#course_id');
      inputCourseId.value = courseId;

      fetch(`{{ route('student.getTeori') }}`)
        .then(response => response.json())
        .then(data => {
          const holidays = data.holidays; // Daftar hari libur
          const fullDates = data.datesWithFullSessions; // Daftar tanggal dengan sesi penuh
          const minDate = button.getAttribute('data-start-date');
          const maxDate = button.getAttribute('data-end-date');

          flatpickr("#session_date", {
            dateFormat: "Y-m-d",
            minDate: minDate,
            maxDate: maxDate,
            disable: [
              function(date) {
                // Nonaktifkan tanggal sesuai libur dan tanggal yang penuh
                return holidays.includes(flatpickr.formatDate(date, "Y-m-d")) ||
                  fullDates.includes(flatpickr.formatDate(date, "Y-m-d"));
              }
            ]
          });
        })
        .catch(error => console.error('Error fetching dates:', error));
    });
  });

  document.querySelector('.modal-footer .btn.bg-gradient-primary').addEventListener('click', function() {
    document.querySelector('.modal-body form').submit(); // Submit the form
  });
</script>
@endsection