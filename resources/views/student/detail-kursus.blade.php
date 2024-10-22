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
<div class="container py-2">
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
    <div class="col-12 mb-3">
      <div class="card">
        <div class="card-header pb-0 p-3">
          <div class="row justify-content-between">
            <div class="col-md-8 d-flex align-items-center">
              <h6 class="mb-0 text-red">Teori</h6>
            </div>
          </div>
        </div>
        <div class="card-body p-3">
          @if($theories->isEmpty())
          <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
            <div class="bg-red-100 alert border border-red d-flex flex-column align-items-center justify-content-center w-100 text-red" role="alert">
              <img src="{{ asset('assets/img/icons/Warning.png') }}" alt="">
              <span>Belum ada teori yang terjadwal!</span>
            </div>
          </li>
          @else
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 w-100" id="kursus-selesai">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Tanggal Teori
                  </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach($theories as $theory)
                <tr>
                  <td><i style="font-size: xx-small;" class="fas fa-circle {{ $theory->session_date <= now()->toDateString() ? 'text-success' : 'text-secondary' }}"></i> {{$theory->session_date}}</td>
                  <td>
                    @if($theory->status === 'scheduled')
                    <span class="badge badge-sm bg-gradient-warning">{{ucfirst($theory->status)}}</span>
                    @elseif($theory->status === 'completed')
                    <span class="badge badge-sm bg-gradient-success">{{ucfirst($theory->status)}}</span>
                    @elseif($theory->status === 'canceled')
                    <span class="badge badge-sm bg-gradient-danger">{{ucfirst($theory->status)}}</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div>
    
    <div class="col-12">
      <div class="card">
        <div class="card-header pb-0 p-3">
          <div class="row justify-content-between">
            <div class="col-md-8 d-flex align-items-center">
              <h6 class="mb-0 text-red">Praktek</h6>
            </div>
          </div>
          <div>
            @if($course->jumlah_teori < 1)
              <span class="text-danger"><i class="fas fa-exclamation-circle"></i> Buat jadwal teori terlebih dahulu</span>
              @elseif($course->jumlah_praktek < $course->class->num_of_praktek)
                <span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{$course->sisa_praktek}} dari total {{$course->class->num_of_praktek}} praktek belum dijadwalkan</span>
                @endif
          </div>
        </div>
        <div class="card-body p-3">
          @if($practices->isEmpty())
          <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
            <div class="bg-red-100 alert border border-red d-flex flex-column align-items-center justify-content-center w-100 text-red" role="alert">
              <img src="{{ asset('assets/img/icons/Warning.png') }}" alt="">
              <span>Belum ada praktek yang terjadwal!</span>
            </div>
          </li>
          @else
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 w-100" id="kursus-selesai">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Tanggal Praktek
                  </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Sesi
                  </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Talent
                  </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach($practices as $practice)
                <tr>
                  <td><i style="font-size: xx-small;" class="fas fa-circle {{ $practice->session_date <= now()->toDateString() ? 'text-success' : 'text-secondary' }}"></i> {{$practice->session_date}}</td>
                  <td>{{ucfirst($practice->session_time)}}</td>
                  <td>
                    @if($practice->talents->isEmpty())
                    <span class="text-danger">Belum ada talent</span>
                    @else
                    @foreach($practice->talents as $talent)
                    <span class="badge badge-sm bg-gradient-secondary">{{ucwords($talent->name)}}</span>
                    @endforeach
                    @endif
                  </td>
                  <td>
                    @if($practice->status === 'scheduled')
                    <span class="badge badge-sm bg-gradient-warning">{{ucfirst($practice->status)}}</span>
                    @elseif($practice->status === 'completed')
                    <span class="badge badge-sm bg-gradient-success">{{ucfirst($practice->status)}}</span>
                    @elseif($practice->status === 'canceled')
                    <span class="badge badge-sm bg-gradient-danger">{{ucfirst($practice->status)}}</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
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
</script>
@endsection