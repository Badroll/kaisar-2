@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row justify-content-between">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-0 text-red">Informasi Kursus</h6>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{route('kursus.edit', $course->id)}}">
                                    <i class="fas fa-edit text-red" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Nama Kelas</strong></li>
                            <li class="list-group-item border-0 ps-0 pt-0">{{ $course->class->name }}</li>
                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Tanggal Mulai</strong></li>
                            <li class="list-group-item border-0 ps-0 pt-0">{{ $course->start_date }}</li>
                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Tanggal Selesai</strong></li>
                            <li class="list-group-item border-0 ps-0 pt-0">{{ $course->end_date }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row justify-content-between">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-0 text-red">Profil Siswa</h6>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{route('users.edit', $user->id)}}">
                                    <i class="fas fa-user-edit text-red" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Nama Lengkap</strong></li>
                            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->name }}</li>
                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Tanggal Lahir</strong></li>
                            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->tgl_lahir }}</li>
                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email</strong></li>
                            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->email }}</li>
                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Nomor HP</strong></li>
                            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->phone_number }}</li>
                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Alamat</strong></li>
                            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->address }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row justify-content-between">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-0 text-red">Teori</h6>
                            </div>
                            @if($course->jumlah_teori < 1)
                                <div class="col-md-4 text-end">
                                <button class="btn bg-gradient-primary btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#teoriModal" id="buatTeori">+&nbsp; Buat Jadwal</button>
                        </div>
                        @endif
                    </div>
                    @if($course->jumlah_teori < 1)
                        <span class="text-danger"><i class="fas fa-exclamation-circle"></i> Teori belum dijadwalkan</span>
                        @endif
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
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Action
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
                                            <td>
                                                <a href="{{ route('teori.edit', $theory->id) }}" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit Teori">
                                                    <i class="fas fa-user-edit text-white"></i>
                                                </a>
                                                <form action="{{ route('teori.destroy', $theory->id) }}" method="post" class="d-inline" onsubmit="return confirm('Apakah kamu yakin ingin menghapus teori ini?')">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-danger" data-toggle="tooltip" data-original-title="Delete Praktek">
                                                        <i class="cursor-pointer fas fa-trash text-white"></i>
                                                    </button>
                                                </form>
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
    <div class="row mt-3">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="row justify-content-between">
                        <div class="col-md-8 d-flex align-items-center">
                            <h6 class="mb-0 text-red">Praktek</h6>
                        </div>
                        @if($course->sisa_praktek > 0 && $course->jumlah_teori >= 1)
                        <div class="col-md-4 text-end">
                            <a href="{{route('kursus.buat-jadwal', $course->id)}}" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Buat Jadwal</a>
                        </div>
                        @endif
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
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
                                    <td>
                                        <a href="{{ route('praktek.edit', $practice->id) }}" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit Praktek">
                                            <i class="fas fa-user-edit text-white"></i>
                                        </a>
                                        <form action="{{ route('praktek.destroy', $practice->id) }}" method="post" class="d-inline" onsubmit="return confirm('Apakah kamu yakin ingin menghapus praktek ini?')">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger" data-toggle="tooltip" data-original-title="Delete Praktek">
                                                <i class="cursor-pointer fas fa-trash text-white"></i>
                                            </button>
                                        </form>
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
                <form action="{{ route('teori.store') }}" method="POST" role="form text-left" enctype="multipart/form-data">
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="course_id" class="form-control-label">Pilih Kursus Siswa<span class="text-danger">*</span></label>
                                <select class="form-control" id="course_id" name="course_id">
                                    <option value="{{$course->id}}" selected>{{ucwords($course->user->name)}} - {{$course->class->name}} ({{$course->sisa_praktek}} sesi belum terjadwal)</option>
                                </select>
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
                    <div class="row">
                        <div class="col-md-12">
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
    document.addEventListener("DOMContentLoaded", function() {
        const buatTeoriButton = document.getElementById('buatTeori');

        buatTeoriButton.addEventListener('click', function() {
            // Fetch data holidays dan datesWithFullSessions secara asinkron
            fetch(`{{ route('teori.getDates') }}`)
                .then(response => response.json())
                .then(data => {
                    const holidays = data.holidays; // Daftar hari libur
                    const fullDates = data.datesWithFullSessions; // Daftar tanggal dengan sesi penuh
                    const minDate = "{{$course->start_date}}"; // Tanggal mulai kursus
                    const maxDate = "{{$course->end_date}}";

                    // Terapkan flatpickr
                    flatpickr("#session_date", {
                        dateFormat: "Y-m-d",
                        minDate: minDate, // Rentang tanggal mulai
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
</script>

<script>
    // Handle form submission when "Simpan" button is clicked
    document.querySelector('.modal-footer .btn.bg-gradient-primary').addEventListener('click', function() {
        document.querySelector('.modal-body form').submit(); // Submit the form
    });
</script>
@endsection