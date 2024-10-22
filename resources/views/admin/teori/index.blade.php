@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4 ms-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column justify-content-between">
                        <h5 class="mb-0">Jadwal Teori Hari Ini</h5>
                        <ul class="list-group">
                            @foreach($today_theories as $theory)
                            <li class="list-group-item my-2 rounded-3 border-1 p-3 border-red shadow" style="background: linear-gradient(91deg, #FFE5F3 15.23%, #F2E0FF 73.91%);">
                                <div class="row">
                                    <div class="col-2">
                                        <img src="{{ ($theory->course->user->photo_path ? asset('storage/' . $theory->course->user->photo_path) : asset('assets/img/user-1.jpg'))}}" 
                                        class="w-100 rounded-3 border border-white border-2 shadow" 
                                        style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                                        alt="">
                                    </div>
                                    <div class="col-10 px-0 align-center">
                                        <h6 class="text-red fw-black mb-0 lh-sm">{{ucwords($theory->course->user->name)}}</h6>
                                        <p class="text-dark">{{ucwords($theory->course->class->name)}}</p>
                                    </div>
                                </div>
                            </li>
                            @endforeach

                            @if($today_theories->isEmpty())
                            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                <div class="bg-red-100 alert border border-red d-flex flex-column align-items-center justify-content-center w-100 text-red" role="alert">
                                    <img src="{{ asset('assets/img/icons/Warning.png') }}" alt="">
                                    <span>Tidak ada kelas teori hari ini</span>
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
                        <h5 class="mb-0">Jadwal Teori Besok</h5>
                        <ul class="list-group">
                            @foreach($tomorrow_theories as $theory)
                            <li class="list-group-item my-2 rounded-3 border-1 p-3 border-red shadow" style="background: linear-gradient(91deg, #FFE5F3 15.23%, #F2E0FF 73.91%);">
                                <div class="row">
                                    <div class="col-2">
                                        <img src="{{ ($theory->course->user->photo_path ? asset('storage/' . $theory->course->user->photo_path) : asset('assets/img/user-1.jpg'))}}" 
                                        class="w-100 rounded-3 border border-white border-2 shadow" 
                                        style="width: 100%; height: auto; aspect-ratio: 1 / 1; object-fit: cover;"
                                        alt="">
                                    </div>
                                    <div class="col-10 px-0 align-center">
                                        <h6 class="text-red fw-black mb-0 lh-sm">{{ucwords($theory->course->user->name)}}</h6>
                                        <p class="text-dark">{{ucwords($theory->course->class->name)}}</p>
                                    </div>
                                </div>
                            </li>
                            @endforeach

                            @if($tomorrow_theories->isEmpty())
                            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                <div class="bg-red-100 alert border border-red d-flex flex-column align-items-center justify-content-center w-100 text-red" role="alert">
                                    <img src="{{ asset('assets/img/icons/Warning.png') }}" alt="">
                                    <span>Tidak ada kelas teori Besok</span>
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
                            <h5 class="mb-0">Teori Terjadwal</h5>
                        </div>
                        <a href="{{ route('teori.create') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Buat Teori</a>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 w-100" id="praktek-aktif">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Teori
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Siswa
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kelas
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
                            <h5 class="mb-0">History Teori</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 w-100" id="kursus-selesai">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Teori
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Siswa
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kelas
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
            "ajax": "{{ route('teori.data.aktif') }}",
            "order": [[0, 'desc']],
            "columns": [
                {
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
            "ajax": "{{ route('teori.data.selesai') }}", // Sesuaikan rutenya
            "columns": [
                {
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