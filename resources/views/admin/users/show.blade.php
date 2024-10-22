@extends('layouts.user_type.admin')

@section('content')

<div>
  <div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
      <span class="mask bg-gradient-primary opacity-6"></span>
    </div>
    <div class="card card-body blur shadow-blur mx-4 mt-n6">
      <div class="row gx-4">
        <div class="col-auto">
          <div class="avatar avatar-xl position-relative">
            <img src="{{ ($user->photo_path ? asset('storage/' . $user->photo_path) : asset('assets/img/user-1.jpg'))}}" class="w-100 h-100 border-radius-lg shadow-sm" style="object-fit: cover;">
            <a type="button" href="{{route('users.edit', $user->id)}}" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
              <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Image"></i>
            </a>
          </div>
        </div>
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">
              {{ ucwords($user->name) }}
            </h5>
            <p class="mb-0 font-weight-bold text-sm">
              @foreach($user->roles as $role)
              <span class="badge badge-sm bg-gradient-secondary">{{$role->name}}</span>
              @endforeach
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12 col-xl-6">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <div class="row justify-content-between">
              <div class="col-md-8 d-flex align-items-center">
                <h6 class="mb-0 text-red">Profil Pengguna</h6>
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
              <li class="list-group-item border-0 ps-0 pt-0">{{ ucwords($user->name) }}</li>
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
      @if($user->roles->contains('name', 'student'))
      <div class="col-12 col-xl-6">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <div class="row justify-between align-items-center">
              <div class="col-6">
                <h6 class="mb-0 text-red">Kursus</h6>
              </div>
              <div class="col text-end">
                <button class="btn bg-gradient-primary btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#courseModal">+&nbsp; Buat Kursus</a>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            <ul class="list-group">
              @foreach($courses as $course)
              <li class="list-group-item rounded-3 mb-2 border-0 d-flex flex-column p-3 bg-gradient-primary">
                <div class="d-flex justify-between align-items-center w-100">
                  <h6 class="text-white mb-0">{{$course->class->name}}</h6>

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

                <div>
                  <span class="text-white text-sm">{{$course->start_date}} - {{$course->end_date}}</span>
                </div>

                <div class="d-flex mt-0 justify-content-end">
                  <a href="{{route('kursus.show', $course->id)}}" title="Lihat Detail Kursus" class="btn btn-primary border border-2 border-white py-2 px-3 mb-0">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="{{route('kursus.edit', $course->id)}}" title="Edit Kursus" class="btn btn-warning border border-2 border-white py-2 px-3 mb-0 ms-2">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  <form action="{{route('kursus.destroy', $course->id)}}" method="post" class="d-inline">
                          @csrf()
                          @method('DELETE')
                          <button class="btn btn-danger border border-2 border-white py-2 px-3 mb-0 ms-2" title="Hapus Kursus" data-toggle="tooltip" data-original-title="Delete user" onclick="return confirm('Apakah kamu yakin ingin menghapus kursus ini?')">
                            <i class="fas fa-trash"></i>
                          </button>
                    </form>
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
      @endif
      @if($user->roles->contains('name', 'student'))
      <div class="col-12 mt-4">
        <div class="card mb-4">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-1 text-red">Teori</h6>
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
                      <a href="{{ route('teori.edit', $theory->id) }}" title="Edit Teori" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit Teori">
                        <i class="fas fa-user-edit text-white"></i>
                      </a>
                      <form action="{{ route('teori.destroy', $theory->id) }}" method="post" class="d-inline" onsubmit="return confirm('Apakah kamu yakin ingin menghapus teori ini?')">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger" title="Hapus Teori" data-toggle="tooltip" data-original-title="Delete Teori">
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
      <div class="col-12 mt-4">
        <div class="card mb-4">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-1 text-red">Praktek</h6>
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
                      <a href="{{ route('praktek.edit', $practice->id) }}" title="Edit Praktek" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                        <i class="fas fa-user-edit text-white"></i>
                      </a>
                      <form action="{{ route('praktek.destroy', $practice->id) }}" method="post" class="d-inline" onsubmit="return confirm('Apakah kamu yakin ingin menghapus praktek ini?')">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger" title="Hapus Praktek" data-toggle="tooltip" data-original-title="Delete Praktek">
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
      @endif
      @if($user->roles->contains('name', 'talent'))
      <div class="col-12 mt-4">
        <div class="card mb-4">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-1 text-red">Potong Rambut</h6>
          </div>
          <div class="card-body p-3">
            @if($practiceTalent->isEmpty())
            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
              <div class="bg-red-100 alert border border-red d-flex flex-column align-items-center justify-content-center w-100 text-red" role="alert">
                <img src="{{ asset('assets/img/icons/Warning.png') }}" alt="">
                <span>Belum ada potong rambut yang terjadwal!</span>
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
                      Waktu
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      Student
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($practiceTalent as $practicet)
                  <tr>
                    <td><i style="font-size: xx-small;" class="fas fa-circle {{ $practicet->practice->session_date <= now()->toDateString() ? 'text-success' : 'text-secondary' }}"></i> {{$practicet->practice->session_date}}</td>
                    <td>{{ucfirst($practicet->practice->session_time)}}</td>
                    <td>
                      @if($practicet->practice->session_time === 'siang')
                        @if($practicet->session_time === '1')
                        14.00
                        @elseif($practicet->session_time === '2')
                        15.00
                        @else
                        17.00
                        @endif
                      @else
                        @if($practicet->session_time === '1')
                        18.30
                        @elseif($practicet->session_time === '2')
                        19.30
                        @else
                        20.30
                        @endif
                      {{ucfirst($practicet->session_time)}}
                      @endif
                    </td>
                    <td>
                      {{ucwords($practicet->practice->course->user->name)}}
                    </td>
                    <td>
                      <a href="{{ route('praktek.edit', $practicet->practice->id) }}" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                        <i class="fas fa-user-edit text-white"></i>
                      </a>
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
      @endif
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" role="dialog" aria-labelledby="courseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="courseModalLabel">Tambah Kursus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('kursus.store') }}" method="POST" role="form text-left">
          @csrf
          <div class="form-group">
            <label for="user-id" class="form-control-label">Siswa<span class="text-danger">*</span></label>
            <select class="form-control" id="siswa" name="user_id">
              <option value="{{$user->id}}" selected>{{$user->name}}</option>
            </select>
            @error('user_id')
            <p class="text-danger text-xs mt-2">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group">
            <label for="kursus-class" class="form-control-label">Kelas<span class="text-danger">*</span></label>
            <select class="form-control" id="kelas" name="class_id">
              @foreach($classes as $class)
              <option value="{{$class->id}}">{{$class->name}}</option>
              @endforeach
            </select>
            @error('class_id')
            <p class="text-danger text-xs mt-2">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group">
            <label for="kursus.date" class="form-control-label">Tanggal Mulai Kursus<span class="text-danger">*</span></label>
            <div class="">
              <input class="form-control @error('start_date') border border-danger @enderror" type="date" value="{{ old('start_date') }}" id="start_date" name="start_date">
            </div>
            @error('start_date')
            <p class="text-danger text-xs mt-2">{{ $message }}</p>
            @enderror
            <p class="text-xs text-secondary mt-2">Tanggal Akhir akan ditentukan otomatis sesuai kelas yang dipilih</p>
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
<script>
  // Handle form submission when "Simpan" button is clicked
  document.querySelector('.modal-footer .btn.bg-gradient-primary').addEventListener('click', function() {
    document.querySelector('.modal-body form').submit(); // Submit the form
  });
</script>
@endsection