@extends('dashboard.base')

@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center mr-2" style="height: 80vh;">
        <img src="{{asset('storage/general_icons/Vttor.png')}}" width="40%">
        <h2 class="mt-4 bold" style="color: #C00120"><center>Bem Vindo, {{Auth::user()->name}}</center></h1>
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
