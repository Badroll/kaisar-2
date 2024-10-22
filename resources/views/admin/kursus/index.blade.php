@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Kursus yang Sedang Berjalan</h5>
                        </div>
                        <a href="{{ route('kursus.create') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; Buat Kursus</a>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 w-100" id="kursus-aktif">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Siswa
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kelas
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Mulai
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Selesai
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
                            <h5 class="mb-0">Kursus yang Sudah Selesai</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 w-100" id="kursus-selesai">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Siswa
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Kelas
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Mulai
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal Selesai
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
        $('#kursus-aktif').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('kursus.data.aktif') }}", // Sesuaikan rutenya
            "order": [
                [2, 'desc']
            ],
            "columns": [
                {
                    data: 'alert',
                    name: 'alert',
                    orderable: false,
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
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
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
            "ajax": "{{ route('kursus.data.selesai') }}",
            "order": [
                [3, 'desc']
            ],
            "columns": [{
                    data: 'student_name',
                    name: 'student_name',
                },
                {
                    data: 'class_name',
                    name: 'class_name',
                    orderable: false,
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
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