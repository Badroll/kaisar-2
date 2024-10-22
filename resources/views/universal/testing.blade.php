@extends('layouts/user_type/blank')

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
    <h1>Buat Tes</h1>
    <form action="">
        <button></button>
    </form>
</div>
@endsection

@section('script')

@endsection