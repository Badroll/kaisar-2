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
                        <button type="button" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2" data-bs-toggle="modal" data-bs-target="#photoModal">
                            <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Image"></i>
                        </button>
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->name }}
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
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Profil Pengguna') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('admin.profil.store') }}" method="POST" role="form text-left">
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
                        <div class="col-12">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">Nama Lengkap<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('name') border border-danger @enderror" type="text" value="{{ old('name', auth()->user()->name) }}" placeholder="Nama Lengkap" id="user-name" name="name">
                                </div>
                                @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user.tgl_lahir" class="form-control-label">Tanggal Lahir</label>
                                <div class="">
                                    <input class="form-control @error('tgl_lahir') border border-danger @enderror" type="date" placeholder="Tanggal Lahir" value="{{ old('tgl_lahir', $user->tgl_lahir) }}" id="tgl_lahir" name="tgl_lahir" value="{{ auth()->user()->tgl_lahir }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">Email<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('email') border border-danger @enderror" type="email" value="{{ old('email', $user->email) }}" placeholder="@example.com" id="user-email" name="email">
                                </div>
                                @error('email')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user.phone" class="form-control-label">Nomor HP<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('phone_number') border border-danger @enderror" type="tel" value="{{ old('phone_number', $user->phone_number) }}" placeholder="08123xxx" id="number" name="phone_number">
                                </div>
                                @error('phone_number')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="user.location" class="form-control-label">Alamat</label>
                            <div class="">
                                <input class="form-control @error('address') border border-danger @enderror" type="text" placeholder="Alamat" value="{{ old('address', $user->address) }}" id="name" name="address" value="{{ auth()->user()->location }}">
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

<!-- Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="photoModalLabel">Ganti Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.profil.photo-update') }}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Tampilkan Foto Profil Saat Ini -->
                    <div class="form-group">
                        <label for="photo">Foto Profil Saat Ini</label>
                        <div>
                            <img id="img-preview" class="img-thumbnail" width="150" height="150" alt="Pratinjau Foto Baru" style="object-fit: cover;" hidden>
                        </div>
                        @if($user->photo_path)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $user->photo_path) }}" id="img-now" class="img-thumbnail" width="150" alt="Foto Profil">
                        </div>
                        <button type="button" id="deletePhoto" class="btn btn-outline-danger">Hapus Foto</button>
                        @else
                        <p>Pengguna belum memiliki foto profil</p>
                        @endif
                    </div>
                    <label for="formFile" class="form-label">Foto Profil</label>
                    <input class="form-control" type="file" id="photo" name="photo">
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
    document.getElementById('photo').addEventListener('change', function(event) {
        // Get the input element and the selected file
        const file = event.target.files[0];
        console.log(file);
        if (file) {
            // Check if file is an image
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create or update an img tag to show the image preview
                const imgPreview = document.getElementById('img-preview');
                const imgNow = document.getElementById('img-now');
                if (imgNow) {
                    imgNow.style.display = "none";
                }
                if (imgPreview) {
                    imgPreview.src = e.target.result; // Update existing image source
                    imgPreview.removeAttribute("hidden");
                } else {
                    // Create new image tag if it doesn't exist
                    const imgElement = document.createElement('img');
                    imgElement.id = 'img-preview';
                    imgElement.src = e.target.result;
                    imgElement.className = 'img-thumbnail';
                    imgElement.width = 150;
                    document.querySelector('.modal-body').appendChild(imgElement);
                }
            };
            reader.readAsDataURL(file); // Read the file as a Data URL
        }
    });

    document.getElementById('deletePhoto').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
            fetch('{{ route("admin.delete-photo") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Sembunyikan foto yang ada
                        document.getElementById('img-now').style.display = 'none';
                        alert('Foto berhasil dihapus');
                    } else {
                        alert('Gagal menghapus foto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }
    });

    // Handle form submission when "Simpan" button is clicked
    document.querySelector('.modal-footer .btn.bg-gradient-primary').addEventListener('click', function() {
        document.querySelector('.modal-body form').submit(); // Submit the form
    });
</script>
@endsection