@extends('layouts.app')

@section('auth')

<main class="position-relative max-height-vh-100 h-100 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}" style="overflow-x: hidden;">
    @include('universal.nav')
    <div class="py-4">
        @yield('content')
    </div>
</main>

@endsection