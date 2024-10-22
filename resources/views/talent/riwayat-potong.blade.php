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
  <div class="row">
    <div class="col-md-6 mb-4">
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="text-red mb-0">Riwayat Potong Rambut</h5>
        </div>
        <div class="row row-cols-1 row-cols-md-2">
          @if($practices_history->isEmpty())
          <div class="col-12">
            <li class="list-group-item border-0 d-flex align-items-center p-0 mb-2">
              <div class="bg-blue-100 mb-0 alert border border-blue d-flex flex-column align-items-center justify-content-center w-100 text-blue" role="alert">
                <img src="{{ asset('assets/img/icons/Sad.png') }}" width="50" height="50" alt="">
                <span class="fw-bold">Belum Ada History Potong Rambut</span>
              </div>
            </li>
          </div>
          @endif
          @foreach($practices_history as $practice)
          <div class="col">
            <li class="list-group-item rounded-3 mb-2 border-0 d-flex flex-column p-3 bg-gradient-primary">
              <div class="d-flex justify-between align-items-center w-100 mb-2">
                <h6 class="text-teori font-sm mb-0">POTONG RAMBUT <i class="fas fa-cut"></i></h6>
              </div>
              <div class="d-inline-flex">
                <div>
                  <h5 class="text-kursus mb-0">
                    {{ \Carbon\Carbon::parse($practice->practice->session_date)->translatedFormat('l, d/m/Y') }}
                  </h5>
                  @if($practice->practice->session_time === 'siang')
                  <p class="text-light text-sm mb-0">Siang</p>
                  @else
                  <p class="text-light text-sm mb-0">Malam</p>
                  @endif
                  <p class="text-white mb-0">
                    @if($practice->practice->session_time === 'siang')
                    @if($practice->session_time === '1')
                    14.00 - 15.00
                    @elseif($practice->session_time === '2')
                    15.00 - 16.00
                    @else
                    16.00 - 17.00
                    @endif
                    @else
                    @if($practice->session_time === '1')
                    18.30 - 19.30
                    @elseif($practice->session_time === '2')
                    19.30 - 20.30
                    @else
                    20.30 - 21.30
                    @endif
                    {{ucfirst($practice->session_time)}}
                    @endif
                  </p>
                </div>
              </div>
            </li>
          </div>
          @endforeach
        </div>
        {{ $practices_history->onEachSide(3)->links() }}
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