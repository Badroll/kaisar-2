@extends('layouts.user_type.admin')

@section('content')

<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Edit User') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('users.update', $user->id) }}" method="POST" role="form text-left" enctype="multipart/form-data">
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
                                <label for="user-name" class="form-control-label">Nama Lengkap<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control @error('name') border border-danger @enderror" type="text" value="{{ old('name', $user->name) }}" placeholder="Nama Lengkap" id="user-name" name="name">
                                </div>
                                @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-tgl_lahir" class="form-control-label">Tanggal Lahir</label>
                                <div class="">
                                    <input class="form-control @error('tgl_lahir') border border-danger @enderror" type="date" value="{{ old('tgl_lahir', $user->tgl_lahir) }}" placeholder="Tanggal Lahir" id="user-tgl_lahir" name="tgl_lahir">
                                </div>
                                @error('tgl_lahir')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-password" class="form-control-label">Password (Baru)</label>
                                <div class="">
                                    <input class="form-control @error('password')border border-danger @enderror" type="text" value="{{ old('password') }}" placeholder="Password" id="user-email" name="password">
                                </div>
                                @error('password')
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
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Tampilkan Foto Profil Saat Ini -->
                            <div class="form-group">
                                <label for="photo">Foto Profil Saat Ini</label>
                                <div id="photo-now">
                                    @if($user->photo_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $user->photo_path) }}" id="img-now" class="img-thumbnail" width="150" alt="Foto Profil">
                                    </div>
                                    <button type="button" id="deletePhoto" class="btn btn-outline-danger">Hapus Foto</button>
                                    @else
                                    <p>Pengguna belum memiliki foto profil</p>
                                    @endif
                                </div>
                            </div>
                            <label for="formFile" class="form-label">Foto Profil</label>
                            <div>
                                <img id="img-preview" class="img-thumbnail" width="150" height="150" alt="Pratinjau Foto Baru" style="object-fit: cover;" hidden>
                            </div>
                            <input class="form-control" type="file" id="photo" name="photo">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roles">Pilih Role (Minimal 1 Role)</label>
                                @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="role-{{ $role->id }}" name="roles[]" value="{{ $role->id }}"
                                        @if(in_array($role->id, $user->roles->pluck('id')->toArray())) checked @endif>
                                    <label class="form-check-label" for="role-{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                                @endforeach
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
                    document.getElementById('photo-now').display = "none";
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
                }
            };
            reader.readAsDataURL(file); // Read the file as a Data URL
        }
    });

    document.getElementById('deletePhoto').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
            fetch('{{ route("users.delete-photo", $user->id) }}', {
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
                        document.getElementById('deletePhoto').style.display = 'none';
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
</script>
@endsection()