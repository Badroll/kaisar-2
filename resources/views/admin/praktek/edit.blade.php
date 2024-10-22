@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Edit Praktek') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('praktek.update', $practice->id) }}" method="POST" role="form text-left" enctype="multipart/form-data">
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
                                    <option value="{{$practice->course->id}}" student-name="{{ $practice->course->user->name }}" selected>{{ucwords($practice->course->user->name)}} - {{$practice->course->class->name}} ({{$practice->course->sisa_praktek}} sesi belum terjadwal)</option>
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
                                    <option value="scheduled" {{old('status', $practice->status) === 'scheduled' ? 'selected' : ''}}>Scheduled</option>
                                    <option value="completed" {{old('status', $practice->status) === 'completed' ? 'selected' : ''}}>Completed</option>
                                    <option value="canceled" {{old('status', $practice->status) === 'canceled' ? 'selected' : ''}}>Canceled</option>
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
                                <label for="session_date" class="form-control-label">Tanggal Praktek<span class="text-danger">*</span></label>
                                <p class="text-xs text-secondary">Pilih tanggal untuk melihat sesi yang tersedia</p>
                                <input class="form-control @error('session_date') border border-danger @enderror" type="date" id="session_date" name="session_date" value="{{ old('session_date', $practice->session_date) }}">
                                @error('session_date')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Loader -->
                    <div id="loading-spinner" style="display: none;">
                        <span class="loading-spinner"></span><span class="fw-bold"> Loading ...</span>
                    </div>
                    <div class="row" id="session-row" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="session_time" class="form-control-label">Pilih Sesi<span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session_time" id="sesi1" value="siang" {{ old('session_time', $practice->session_time) == 'siang' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sesi1" id="sesi1-label">Sesi 1</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session_time" id="sesi2" value="malam" {{ old('session_time', $practice->session_time) == 'malam' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sesi2" id="sesi2-label">Sesi 2</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="talent_1" class="form-control-label">Talent 1</label>
                                <select class="form-control" id="talent_1" name="talent_1">
                                    <option value="" selected disabled>Pilih Talent</option>
                                    @foreach($talents as $talent)
                                    <option value="{{$talent->id}}" {{ old('talent_1', $practice->talent_1) == $talent->id ? 'selected' : '' }}>{{ucwords($talent->name)}}</option>
                                    @endforeach
                                </select>
                                @error('talent_1')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="talent_2" class="form-control-label">Talent 2</label>
                                <select class="form-control" id="talent_2" name="talent_2">
                                    <option value="" selected disabled>Pilih Talent</option>
                                    @foreach($talents as $talent)
                                    <option value="{{$talent->id}}" {{ old('talent_2', $practice->talent_2) == $talent->id ? 'selected' : '' }}>{{ucwords($talent->name)}}</option>
                                    @endforeach
                                </select>
                                @error('talent_2')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="talent_3" class="form-control-label">Talent 3</label>
                                <select class="form-control" id="talent_3" name="talent_3">
                                    <option value="" selected disabled>Pilih Talent</option>
                                    @foreach($talents as $talent)
                                    <option value="{{$talent->id}}" {{ old('talent_3', $practice->talent_3) == $talent->id ? 'selected' : '' }}>{{ucwords($talent->name)}}</option>
                                    @endforeach
                                </select>
                                @error('talent_3')
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
        const minDate = "{{$practice->course->start_date}}";  // Tanggal mulai kursus
        const maxDate = "{{$practice->course->end_date}}";


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
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // Lakukan pemanggilan async untuk mengecek sesi
                    fetchAvailableSessions(dateStr);
                }
            }
        });

        const sessionDate = "{{ $practice->session_date }}"; // Mengambil nilai dari PHP
        if (sessionDate) {
            fetchAvailableSessions(sessionDate); // Memanggil fungsi dengan parameter session_date
        }
    });

    function fetchAvailableSessions(date) {
        // Tampilkan animasi loading
        document.getElementById('loading-spinner').style.display = 'block';
        document.getElementById('session-row').style.display = 'none';
        fetch(`/get-sessions-edit/{{$practice->id}}?date=${date}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading-spinner').style.display = 'none';
                const session1 = document.getElementById('sesi1');
                const session2 = document.getElementById('sesi2');
                const courseSelect = document.getElementById("course_id");
                const selectedOption = courseSelect.options[courseSelect.selectedIndex];
                const studentName = selectedOption.getAttribute("student-name");
                const students = data.students;

                console.log(studentName);

                // Tampilkan sisa slot
                document.getElementById('sesi1-label').innerText = `Sesi 1 (Tersedia ${data.sesi1_slots} slot)`;
                document.getElementById('sesi2-label').innerText = `Sesi 2 (Tersedia ${data.sesi2_slots} slot)`;

                // Aktifkan / nonaktifkan sesi berdasarkan slot yang tersedia
                session1.disabled = data.sesi1_slots === 0;
                session2.disabled = data.sesi2_slots === 0;

                if (students.some(student => student.name === studentName)) {
                    session1.disabled = true;
                    session2.disabled = true;
                    alert('Siswa sudah mengambil hari ini, tolong cari tanggal lain.');
                } else if (data.sesi1_slots === 0 && data.sesi2_slots === 0) {
                    alert('Jadwal tanggal ini penuh, tolong cari tanggal lain.');
                }
                
                document.getElementById('session-row').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loading-spinner').style.display = 'none';
            });
    }
</script>


@endsection