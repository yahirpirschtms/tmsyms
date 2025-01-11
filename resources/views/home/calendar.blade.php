@extends('layouts.app-master')

@section('title', 'WH Appointment Viewer')

@section('content')
    @auth
        <div class="container  my-4">
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" >WH Appointment Viewer</h2>
            </div>
            <form>
        </div>

    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection
