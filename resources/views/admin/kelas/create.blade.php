@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Tambah Kelas') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('kelas.store') }}" method="POST" role="form text-left">
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
                                <label for="class-name" class="form-control-label">Nama Kelas<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('name') border border-danger @enderror" type="text" value="{{ old('name') }}" placeholder="Nama Kelas" id="class-name" name="name">
                                </div>
                                @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class-day" class="form-control-label">Durasi (Hari)<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('day_duration') border border-danger @enderror" type="number" value="{{ old('day_duration') }}" placeholder="Durasi (Hari)" id="class-day" name="day_duration">
                                </div>
                                @error('day_duration')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kelas-praktek" class="form-control-label">Jumlah Praktek<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('num_of_praktek')border border-danger @enderror" type="text" value="{{ old('num_of_praktek') }}" placeholder="Jumlah Praktek" id="class-praktek" name="num_of_praktek">
                                </div>
                                @error('num_of_praktek')
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