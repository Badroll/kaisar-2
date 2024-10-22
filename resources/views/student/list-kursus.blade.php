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
  <div class="row gx-5">
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Kursus Aktif</h4>
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
            @if($course->jumlah_teori < 1 && $course->status != 'completed' && $course->status != 'canceled')
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
              @elseif($course->sisa_praktek > 0 && $course->status != 'completed' && $course->status != 'canceled')
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