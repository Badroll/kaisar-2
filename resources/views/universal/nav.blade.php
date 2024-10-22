<nav class="navbar navbar-light bg-light">
  <div class="container py-2">
    @php
    // Ambil segmen pertama dari URL (misalnya 'trainer' atau 'student')
    $userRole = Request::segment(1);

    // Tentukan route berdasarkan role
    switch ($userRole) {
      case 'trainer':
        $homeRoute = route('trainer.home');
        break;
      case 'student':
        $homeRoute = route('student.home');
        break;
      case 'talent':
        $homeRoute = route('talent.home');
        break;
      default:
        $homeRoute = route('choose'); // Pengalihan default jika segmen tidak sesuai
        break;
    }
    @endphp
    <a class="navbar-brand" href="{{$homeRoute}}">
      <img src="{{ asset('assets/img/logos/logo.png') }}" height="30" class="" alt="">
    </a>
    @php
    switch ($userRole) {
      case 'trainer':
        $profilRoute = route('trainer.profil');
        break;
      case 'student':
        $profilRoute = route('student.profil');
        break;
      case 'talent':
        $profilRoute = route('talent.profil');
        break;
      default:
        $profilRoute = route('choose');
        break;
    }
    @endphp
    <div>
      <a class="" href="{{ $profilRoute }}">
        <img src="{{ (auth()->user()->photo_path ? asset('storage/' . auth()->user()->photo_path) : asset('assets/img/user-1.jpg')) }}" alt="" width="40" height="40" class="rounded-circle" style="object-fit: cover;">
      </a>
    </div>
  </div>
</nav>