@extends('layouts/user_type/universal')

@section('title', 'Kaisar Barber Academy')

@section('page-style')
<style>
  .authentication-wrapper {
    display: flex;
    flex-basis: 100%;
    min-height: fit-content;
    width: 100%;
  }

  .h-full {
    height: 100vh;
  }

  .bg-block {
    background: linear-gradient(92deg, #6666D4 8.6%, #B94648 96.18%);
  }

  .border-white {
    border-color: white;
  }

  @media (min-width: 768px) {
    .bg-md-transparent {
      background-color: transparent !important;
    }
  }
</style>
@endsection

@section('content')
<div class="row py-4">
  <!-- Foto Profil -->
  <div class="col-md-6 col-lg-3 d-flex justify-content-center align-items-start py-3">
    <div class="position-relative">
      <img src="{{ ($user->photo_path ? asset('storage/' . $user->photo_path) : asset('assets/img/user-1.jpg'))}}"
        class="rounded-circle border border-5 border-white"
        width="200"
        height="200"
        style="object-fit: cover;">
      <button type="button" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2" data-bs-toggle="modal" data-bs-target="#photoModal">
        <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Image"></i>
      </button>
    </div>
  </div>

  <div class="col-md-6 col-lg-9">
    <!-- Identitas -->
    <div class="container py-3">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <div class="row justify-content-between">
            <div class="d-flex justify-content-between">
              <h6 class="mb-0 text-red">Profil Pengguna</h6>
              <a href="" id="editButton" class="edit">
                <i class="fas fa-user-edit text-red" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="card-body p-3">
          <ul class="list-group" id="userProfil">
            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Nama Lengkap</strong></li>
            <li class="list-group-item border-0 ps-0 pt-0">{{ ucwords($user->name) }}</li>
            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Tanggal Lahir</strong></li>
            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->tgl_lahir }}</li>
            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email</strong></li>
            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->email }}</li>
            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Nomor HP</strong></li>
            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->phone_number }}</li>
            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Alamat</strong></li>
            <li class="list-group-item border-0 ps-0 pt-0">{{ $user->address }}</li>
          </ul>
          @php
          $userRole = Request::segment(1);
          @endphp
          <form action="{{ route( $userRole.'.update-profil') }}" method="POST" role="form text-left" hidden>
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
            <div class="col-12">
              <div class="form-group">
                <label for="user.tgl_lahir" class="form-control-label">Tanggal Lahir</label>
                <div class="">
                  <input class="form-control @error('tgl_lahir') border border-danger @enderror" type="date" placeholder="Tanggal Lahir" value="{{ old('tgl_lahir', $user->tgl_lahir) }}" id="tgl_lahir" name="tgl_lahir" value="{{ auth()->user()->tgl_lahir }}">
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

    <!-- Ganti Password dan Logout -->
    <div class="">
      <div class="bg-white mb-3 mb-md-0 py-3 bg-md-transparent">
        <a href="{{ route($userRole.'.ganti-password') }}" class="container text-red fw-bold"><i class="fas fa-key"></i> Ganti Password</a>
      </div>
      @php
      $userRole = Request::segment(1);
      @endphp
      @if($userRole == 'student')
      <div class="bg-white mb-3 mb-md-0 py-3 bg-md-transparent">
        <a href="{{ route('student.kursus') }}" class="container text-red fw-bold"><i class="far fa-list-alt"></i></i> History Kursus</a>
      </div>
      @endif
      <div class="bg-white mb-3 py-3 bg-md-transparent">
        <form method="post" action="{{ route('logout') }}">
          @csrf
          <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" title="Logout Akun" class="container text-red fw-bold">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
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
        @php
        $userRole = Request::segment(1);

        // Tentukan route berdasarkan role
        switch ($userRole) {
        case 'trainer':
        $updatePhotoRoute = route('trainer.update-photo');
        break;
        case 'student':
        $updatePhotoRoute = route('student.update-photo');
        break;
        case 'talent':
        $updatePhotoRoute = route('talent.update-photo');
        break;
        default:
        $updatePhotoRoute = route('choose'); // Pengalihan default jika segmen tidak sesuai
        break;
        }
        @endphp
        <form action="{{ $updatePhotoRoute }}" method="POST" role="form text-left" enctype="multipart/form-data">
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
  function toTitleCase(str) {
    return str.replace(
      /\w\S*/g,
      function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      }
    );
  }

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

  // Handle form submission when "Simpan" button is clicked
  document.querySelector('.modal-footer .btn.bg-gradient-primary').addEventListener('click', function() {
    document.querySelector('.modal-body form').submit(); // Submit the form
  });

  var deletePhotoButton = document.getElementById('deletePhoto');

  if (deletePhotoButton) {
    deletePhotoButton.addEventListener('click', function() {
      var role = window.location.pathname.split('/')[1];

      if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
        fetch(`/${role}/delete-photo`, {
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
  }

  document.getElementById('editButton').addEventListener('click', function(e) {
    e.preventDefault();

    var editButton = document.getElementById('editButton');
    var userProfile = document.getElementById('userProfil');
    var form = document.querySelector('form');
    var icon = editButton.querySelector('i');

    if (editButton.classList.contains('edit')) {
      // Saat editButton ditekan
      userProfile.style.display = 'none'; // Sembunyikan ul
      form.removeAttribute('hidden'); // Tampilkan form
      icon.classList.remove('fa-user-edit'); // Ganti icon
      icon.classList.add('fa-times-circle');
      editButton.classList.remove('edit');
      editButton.classList.add('close');
    } else {
      // Saat close button ditekan
      userProfile.style.display = 'block'; // Tampilkan ul
      form.setAttribute('hidden', true); // Sembunyikan form
      icon.classList.remove('fa-times-circle'); // Kembalikan icon
      icon.classList.add('fa-user-edit');
      editButton.classList.remove('close');
      editButton.classList.add('edit');
    }
  });
</script>
@endsection