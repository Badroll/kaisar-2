@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Edit Kursus') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('kursus.update', $course->id) }}" method="POST" role="form text-left" enctype="multipart/form-data">
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
                                <label for="user-id" class="form-control-label">Siswa<span class="text-danger">*</span></label>
                                <select class="form-control" id="siswa" name="user_id">
                                    @foreach($students as $student)
                                    <option value="{{$student->id}}" {{ $course->user_id == $student->id ? 'selected' : '' }}>{{$student->name}}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">Kelas<span class="text-danger">*</span></label>
                                <select class="form-control" id="kelas" name="class_id">
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}" {{ $course->class_id == $class->id ? 'selected' : '' }}>{{$class->name}}</option>
                                    @endforeach
                                </select>
                                @error('email')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user.phone" class="form-control-label">Tanggal Mulai Kursus<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('start_date') border border-danger @enderror" type="date" value="{{ old('start_date', $course->start_date) }}" id="start_date" name="start_date">
                                </div>
                                @error('start_date')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user.phone" class="form-control-label">Tanggal Selesai Kursus<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('end_date') border border-danger @enderror" type="date" value="{{ old('end_date', $course->end_date) }}" id="end_date" name="end_date">
                                </div>
                                @error('end_date')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">Status<span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status">
                                    <option value="created" {{ $course->status == 'created' ? 'selected' : '' }}>Created</option>
                                    <option value="canceled" {{ $course->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                    <option value="scheduled" {{ $course->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="completed" {{ $course->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="need_reschedule" {{ $course->status == 'need_reschedule' ? 'selected' : '' }}>Need Reschedule</option>
                                </select>
                                @error('status')
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