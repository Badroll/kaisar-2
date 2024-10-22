@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Edit Teori') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('teori.update', $theory->id) }}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                <select class="form-control" id="course_id" name="course_id" disabled>
                                    <option value="{{$theory->course->id}}" selected>{{ucwords($theory->course->user->name)}} - {{$theory->course->class->name}} ({{$theory->course->sisa_praktek}} sesi belum terjadwal)</option>
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
                                    <option value="scheduled" {{old('status', $theory->status) === 'scheduled' ? 'selected' : ''}}>Scheduled</option>
                                    <option value="completed" {{old('status', $theory->status) === 'completed' ? 'selected' : ''}}>Completed</option>
                                    <option value="canceled" {{old('status', $theory->status) === 'canceled' ? 'selected' : ''}}>Canceled</option>
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
                                <p class="text-xs text-secondary">Pilih tanggal untuk melihat sesi yang tersedia</p>
                                <input class="form-control @error('session_date') border border-danger @enderror" type="date" id="session_date" name="session_date" value="{{ old('session_date', $theory->session_date) }}">
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
        const holidays = @json($holidays);  // Daftar hari libur
        const fullDates = @json($datesWithFullSessions);  // Daftar tanggal dengan sesi penuh
        const minDate = "{{$theory->course->start_date}}";  // Tanggal mulai kursus
        const maxDate = "{{$theory->course->end_date}}";

        flatpickr("#session_date", {
            dateFormat: "Y-m-d",
            minDate: minDate,  // Rentang tanggal mulai
            maxDate: maxDate,
            disable: [
                function(date) {
                    // Nonaktifkan tanggal sesuai libur dan tanggal yang penuh
                    return holidays.includes(flatpickr.formatDate(date, "Y-m-d")) || 
                           fullDates.includes(flatpickr.formatDate(date, "Y-m-d"));
                }
            ],
        });
    });
</script>


@endsection