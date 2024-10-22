@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Buat Teori') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_id" class="form-control-label">Pilih Kursus Siswa<span class="text-danger">*</span></label>
                                <select class="form-control" id="course_id" name="course_id">
                                    <option value="" disabled selected>Pilih siswa</option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}"
                                        data-start="{{ $course->start_date }}"
                                        data-end="{{ $course->end_date }}">
                                        {{ ucwords($course->user->name) }} - {{ $course->class->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        <!-- INPUT TANGGAL DAN SESI -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="session_date" class="form-control-label">Tanggal Teori<span class="text-danger">*</span></label>
                                <input class="form-control @error('session_date') border border-danger @enderror" type="date" id="session_date" name="session_date" value="{{ old('session_date') }}" disabled>
                                @error('session_date')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Simpan</button>
                    </div>
                </form>
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
        const holidays = @json($holidays); // Daftar hari libur
        const fullDates = @json($datesWithFullSessions); // Daftar tanggal dengan sesi penuh

        // Inisialisasi flatpickr
        const flatpickrInstance = flatpickr("#session_date", {
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    // Nonaktifkan tanggal sesuai libur dan tanggal yang penuh
                    return holidays.includes(flatpickr.formatDate(date, "Y-m-d")) ||
                        fullDates.includes(flatpickr.formatDate(date, "Y-m-d"));
                }
            ]
        });

        // Tambahkan event listener untuk select course
        const courseSelect = document.getElementById("course_id");

        courseSelect.addEventListener("change", function() {
            const selectedOption = courseSelect.options[courseSelect.selectedIndex];
            const startDate = selectedOption.getAttribute("data-start");
            const endDate = selectedOption.getAttribute("data-end");
            document.getElementById('session_date').disabled = false;

            // Update minDate dan maxDate di flatpickr berdasarkan kursus yang dipilih
            flatpickrInstance.set('minDate', startDate);
            flatpickrInstance.set('maxDate', endDate);
        });
    });
</script>


@endsection