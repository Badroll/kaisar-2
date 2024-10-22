@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4 ms-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column justify-content-between">
                        <h5 class="mb-2">Jadwal Praktek Hari Ini</h5>
                        <ul class="list-group">
                            @foreach($today_practices as $practice)
                            <li class="list-group-item my-2 rounded-3 p-3 border-1 border-red shadow" style="background: linear-gradient(91deg, #FFE5F3 15.23%, #F2E0FF 73.91%);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-4">
                                                <img src="{{ ($practice->course->user->photo_path ? asset('storage/' . $practice->course->user->photo_path) : asset('assets/img/user-1.jpg'))}}"
                                                    class="w-100 rounded-3 border border-white border-2 shadow"
                                                    style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                                                    alt="">
                                            </div>
                                            <div class="col-8 px-0">
                                                <h6 class="text-red fw-black mb-0 lh-sm">{{ucwords($practice->course->user->name)}}</h6>
                                                <span class="text-dark text-sm">{{ucfirst($practice->session_time)}} ({{$practice->session_time === 'siang' ? '14:00 - 17.00' : '18:30 - 21:00'}})</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-sm text-secondary mb-1">Talent:</h6>
                                        @if($practice->talents->isEmpty())
                                        <div ><span class="badge badge-sm bg-gradient-danger">Belum ada talent</span></div>
                                        @else
                                        <div>
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
                            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                <div class="bg-red-100 alert border border-red d-flex flex-column align-items-center justify-content-center w-100 text-red" role="alert">
                                    <img src="{{ asset('assets/img/icons/Warning.png') }}" alt="">
                                    <span>Tidak ada praktek hari ini</span>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card-body pb-2">

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4 me-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column justify-content-between">
                        <h5 class="mb-2">Jadwal Praktek Besok</h5>
                        <ul class="list-group">
                            @foreach($tomorrow_practices as $practice)
                            <li class="list-group-item my-2 rounded-3 p-3 border-1 border-red shadow" style="background: linear-gradient(91deg, #FFE5F3 15.23%, #F2E0FF 73.91%);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-4">
                                                <img src="{{ ($practice->course->user->photo_path ? asset('storage/' . $practice->course->user->photo_path) : asset('assets/img/user-1.jpg'))}}"
                                                    class="w-100 rounded-3 border border-white border-2 shadow"
                                                    style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                                                    alt="">
                                            </div>
                                            <div class="col-8 px-0">
                                                <h6 class="text-red fw-black mb-0 lh-sm">{{ucwords($practice->course->user->name)}}</h6>
                                                <span class="text-dark text-sm">{{ucfirst($practice->session_time)}} ({{$practice->session_time === 'siang' ? '14:00 - 17.00' : '18:30 - 21:00'}})</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-sm text-secondary mb-1">Talent:</h6>
                                        @if($practice->talents->isEmpty())
                                        <div ><span class="badge badge-sm bg-gradient-danger">Belum ada talent</span></div>
                                        @else
                                        <div>
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

                            @if($tomorrow_practices->isEmpty())
                            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                <div class="bg-red-100 alert border border-red d-flex flex-column align-items-center justify-content-center w-100 text-red" role="alert">
                                    <img src="{{ asset('assets/img/icons/Warning.png') }}" alt="">
                                    <span>Tidak ada praktek Besok</span>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card-body pb-2">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Praktek Terjadwal</h5>
                        </div>
                        <a href="{{ route('praktek.create') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Buat Praktek</a>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 w-100" id="praktek-aktif">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Praktek
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Siswa
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kelas
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

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">History Praktek</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 w-100" id="kursus-selesai">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Praktek
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Siswa
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kelas
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

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    $(document).ready(function() {
        $('#praktek-aktif').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('praktek.data.aktif') }}", // Sesuaikan rutenya
            "columns": [{
                    data: 'session_date',
                    name: 'session_date'
                },
                {
                    data: 'student_name',
                    name: 'student_name'
                },
                {
                    data: 'class_name',
                    name: 'class_name',
                    orderable: false,
                },
                {
                    data: 'session_time',
                    name: 'session_time'
                },
                {
                    data: 'talents',
                    name: 'talents',
                    orderable: false,
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#kursus-selesai').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('praktek.data.selesai') }}",
            "order": [
                [0, 'desc']
            ],
            "columns": [{
                    data: 'session_date',
                    name: 'session_date'
                },
                {
                    data: 'student_name',
                    name: 'student_name'
                },
                {
                    data: 'class_name',
                    name: 'class_name',
                    orderable: false,
                },
                {
                    data: 'session_time',
                    name: 'session_time'
                },
                {
                    data: 'talents',
                    name: 'talents',
                    orderable: false,
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endsection