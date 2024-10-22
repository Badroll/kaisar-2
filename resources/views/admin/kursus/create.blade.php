@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Buat Kursus') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('kursus.store') }}" method="POST" role="form text-left" enctype="multipart/form-data">
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
                                <label for="user-id" class="form-control-label">Siswa<span class="text-danger">*</span></label>
                                <select class="form-control" id="siswa" name="user_id">
                                    @foreach($students as $student)
                                    <option value="{{$student->id}}">{{$student->name}}</option>
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
                                    <option value="{{$class->id}}">{{$class->name}}</option>
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
                                    <input class="form-control @error('start_date') border border-danger @enderror" type="date" value="{{ old('start_date') }}" id="start_date" name="start_date">
                                </div>
                                @error('start_date')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-secondary mt-2">Tanggal Akhir akan ditentukan otomatis sesuai kelas yang dipilih</p>
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