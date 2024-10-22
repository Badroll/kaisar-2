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

  .border-white {
    border-color: white;
  }
</style>
@endsection

@section('content')
<div class="container py-3">
  <div class="row gx-5">
    <div class="col-md-6 mb-3">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <div class="row justify-content-between">
            <div class="col-md-8 d-flex align-items-center">
              <h6 class="mb-0 text-red">Informasi Kursus</h6>
            </div>
          </div>
        </div>
        <div class="card-body p-3">
          <ul class="list-group">
            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Nama Kelas</strong></li>
            <li class="list-group-item border-0 ps-0 py-0">{{ $course->class->name }}</li>
            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Tanggal Mulai</strong></li>
            <li class="list-group-item border-0 ps-0 py-0">{{ $course->start_date }}</li>
            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Tanggal Selesai</strong></li>
            <li class="list-group-item border-0 ps-0 py-0">{{ $course->end_date }}</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <div class="row justify-content-between">
            <div class="col-md-8 d-flex align-items-center">
              <h6 class="mb-0 text-red">Jadwal Praktek</h6>
            </div>
          </div>
        </div>
        <div class="card-body p-3" style="overflow-x: scroll;">
          <table class="table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Sesi Siang</th>
                <th>Sesi Malam</th>
              </tr>
            </thead>
            <tbody>
              @php
              $today = Carbon\Carbon::now();
              if ($startDate < $today) {
                $startDate=$today;
                }
                @endphp
                @for ($date=$startDate; $date <=$endDate; $date->addDay())
                @php
                $maxSlotsPerSession = 2; // Misalnya jumlah maksimal slot per sesi adalah 2
                $siangPractices = isset($other_practices[$date->format('Y-m-d')])
                ? $other_practices[$date->format('Y-m-d')]->where('session_time', 'siang')->count()
                : 0;
                $malamPractices = isset($other_practices[$date->format('Y-m-d')])
                ? $other_practices[$date->format('Y-m-d')]->where('session_time', 'malam')->count()
                : 0;
                @endphp
                @if (!in_array($date->format('Y-m-d'), $holidays))
                <tr>
                  <td class="align-middle text-lg fw-bold">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d/m') }}</td>

                  <!-- Sesi Siang -->
                  <td>
                    @php
                    // Cek apakah ada jadwal untuk tanggal tersebut
                    $siang = isset($student_practices[$date->format('Y-m-d')]) ? $student_practices[$date->format('Y-m-d')]->where('session_time', 'siang')->first() : null;
                    $malam = isset($student_practices[$date->format('Y-m-d')])
                    ? $student_practices[$date->format('Y-m-d')]->where('session_time', 'malam')->first()
                    : null;
                    @endphp

                    @if($malam) <!-- Tambahan: jika sesi malam sudah diambil, disable siang -->
                    <button class="btn btn-secondary" disabled>Pilih Sesi Siang</button>
                    @elseif($siang)
                    <button class="btn btn-success" disabled>Sesi Siang Sudah Diambil</button>
                    @elseif($siangPractices >= $maxSlotsPerSession)
                    <button class="btn btn-secondary" disabled>Sesi Penuh</button>
                    @elseif($course->theories->where('status', '<>', 'canceled')->where('session_date', '>', $date)->isNotEmpty())
                      <button class="btn btn-secondary" disabled>Sesi Teori Belum dilakukan</button>
                      @else
                      <button class="btn btn-outline-primary toggle-session"
                        data-date="{{ $date->format('Y-m-d') }}"
                        data-time="siang"
                        data-max="{{ $course->sisa_praktek }}">
                        Pilih Sesi Siang
                      </button>
                      @endif
                  </td>

                  <!-- Sesi Malam -->
                  <td>
                    @if($siang) <!-- Tambahan: jika sesi siang sudah diambil, disable malam -->
                    <button class="btn btn-secondary" disabled>Pilih Sesi Malam</button>
                    @elseif($malam)
                    <button class="btn btn-success" disabled>Sesi Malam Sudah Diambil</button>
                    @elseif($malamPractices >= $maxSlotsPerSession)
                    <button class="btn btn-secondary" disabled>Sesi Penuh</button>
                    @elseif($course->theories->where('status', '<>', 'canceled')->where('session_date', '>', $date)->isNotEmpty())
                      <button class="btn btn-secondary" disabled>Sesi Teori Belum dilakukan</button>
                      @else
                      <button class="btn btn-outline-primary toggle-session"
                        data-date="{{ $date->format('Y-m-d') }}"
                        data-time="malam"
                        data-max="{{ $course->sisa_praktek }}">
                        Pilih Sesi Malam
                      </button>
                      @endif
                  </td>
                </tr>
                @endif
                @endfor
            </tbody>
          </table>

          <!-- Button Simpan -->
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-info me-2" id="clearSessions">Clear</button>
            <button id="save-schedule" class="btn btn-primary">Simpan Jadwal</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    let selectedSessions = [];

    // Fungsi untuk toggle button
    // Fungsi untuk toggle button
    document.querySelectorAll('.toggle-session').forEach(button => {
      button.addEventListener('click', function() {
        const date = this.dataset.date;
        const time = this.dataset.time;

        // Dapatkan sesi lain (siang/malam) pada tanggal yang sama
        const otherSessionButton = document.querySelector(`.toggle-session[data-date="${date}"][data-time="${time === 'siang' ? 'malam' : 'siang'}"]`);

        // Cek apakah sesi sudah dipilih
        const exists = selectedSessions.find(session => session.date === date && session.time === time);

        if (exists) {
          // Batalkan pilihan sesi
          selectedSessions = selectedSessions.filter(session => session.date !== date || session.time !== time);
          this.classList.remove('btn-success', 'btn-selected');
          this.classList.add('btn-outline-primary');
          this.textContent = `Pilih Sesi ${time.charAt(0).toUpperCase() + time.slice(1)}`;

          // Aktifkan sesi lain (siang/malam) jika sebelumnya dinonaktifkan
          if (otherSessionButton) {
            otherSessionButton.disabled = false;
            otherSessionButton.classList.remove('btn-secondary');
            otherSessionButton.classList.remove('btn-disabled');
            otherSessionButton.classList.add('btn-outline-primary');
          }
        } else {
          // Pilih sesi
          if (selectedSessions.length >= this.dataset.max) {
            alert('Anda sudah memilih maksimal sesi.');
            return;
          }
          selectedSessions.push({
            date: date,
            time: time
          });
          this.classList.remove('btn-outline-primary');
          this.classList.add('btn-success', 'btn-selected');
          this.textContent = `Sesi ${time.charAt(0).toUpperCase() + time.slice(1)} Dipilih`;

          // Nonaktifkan sesi lain (siang/malam)
          if (otherSessionButton) {
            otherSessionButton.disabled = true;
            otherSessionButton.classList.remove('btn-outline-primary');
            otherSessionButton.classList.add('btn-secondary');
            otherSessionButton.classList.add('btn-disabled');
          }
        }
      });
    });

    // Tombol Clear
    document.getElementById('clearSessions').addEventListener('click', function() {
      const selectedButtons = document.querySelectorAll('.btn-selected');
      const disabledButtons = document.querySelectorAll('.btn-disabled');
      selectedButtons.forEach(function(button) {
        const time = button.getAttribute('data-time'); // Ambil waktu dari atribuPt data-time

        button.classList.remove('btn-success');
        button.classList.remove('btn-selected');
        button.classList.add('btn-outline-primary');
        button.textContent = `Pilih Sesi ${time.charAt(0).toUpperCase() + time.slice(1)}`;
      });

      disabledButtons.forEach(function(button) {
        button.disabled = false;
        button.classList.remove('btn-secondary');
        button.classList.remove('btn-disabled');
        button.classList.add('btn-outline-primary');
      });
      selectedSessions = [];
    });

    // Tombol Simpan
    document.getElementById('save-schedule').addEventListener('click', function() {
      let requiredSessions = {{ $course->sisa_praktek }};
      
      if (selectedSessions.length !== requiredSessions) {
        Swal.fire({
          icon: 'warning',
          title: 'Jumlah sesi yang dipilih masing kurang',
          text: 'Anda harus memilih ' + requiredSessions + ' sesi sebelum menyimpan.',
        });
        return;
      }

      fetch(`{{ route('student.store-praktek', $course->id) }}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            sessions: selectedSessions
          })
        })
        .then(response => {
          return response.json().then(data => ({
            status: response.status,
            body: data
          }));
        })
        .then(({
          status,
          body
        }) => {
          if (status === 201) {
            Swal.fire({
              icon: 'success',
              title: 'Uhuyy!',
              text: body.message,
              showConfirmButton: false,
              timer: 1500
            }).then(() => {
              window.location.href = `{{ route('student.home') }}`;
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: body.message || 'Gagal menyimpan jadwal, coba lagi!',
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan dalam memproses jadwal. Silakan coba lagi nanti.',
          });
        });
    });
  });
</script>
@endsection