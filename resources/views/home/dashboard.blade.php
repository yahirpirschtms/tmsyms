@extends('layouts.app-master')

@section('title', 'Empty Trailer')

@section('content')
    @auth

    <script>//almacenar trailers
        let shipmentsData = @json($shipments->keyBy('pk_shipment'));
        console.log(shipmentsData);
    </script>

        <div id="encabezado y filtro" class="container  mt-4 " style=" background-color:white; position:fixed; left:0; right:0; top:80px; padding:10px; padding-bottom:0; z-index:10;" >
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" style="">Empty Trailer</h2>
            </div>

            <!--Botones Añadir y refresh-->
            <div class="d-flex justify-content-end mt-4 mb-2">
                <!--<input class="form-control me-2" type="search" placeholder="Search By Filters" name="searchemptytrailergeneral" id="searchemptytrailergeneral" aria-label="Search">-->
                <div style="position: relative; display: inline-block; width: 100%;" class="me-4">
                    <i 
                        class="fa-solid fa-magnifying-glass" 
                        style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #6c757d; cursor: pointer;"
                        onclick="document.getElementById('searchemptytrailergeneral').focus()">
                    </i>
                    <input 
                        class="form-control" 
                        type="search" 
                        placeholder="    Search By Filters" 
                        name="searchemptytrailergeneral" 
                        id="searchemptytrailergeneral" 
                        aria-label="Search" 
                        style="padding-left: 30px;">
                </div>
                <button type="button" style="color: white;" class="btn me-2 btn-success" id="exportfile" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Export File">
                    <i class="fa-solid fa-file-export"></i>
                </button>
                <button type="button" style="color: white;" class="btn me-2 btn-primary" id="refreshemptytrailertable" data-url="{{ route('emptytrailer.data') }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Refresh Data">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </button>
                <button class="btn me-2" style="color: white;;background-color:rgb(13, 82, 200);" id="addnewemptytrailer" type="button" data-bs-toggle="offcanvas" data-bs-target="#newtrailerempty" aria-controls="offcanvasWithBothOptions">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <button class="btn" id="addmorefiltersemptytrailer" style="color: white;background-color:orange;" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasaddmorefilters" aria-controls="offcanvasaddmorefilters">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>

        </div>

        <div class="container  mb-4" style="margin-top: 290px;">
            <h1>hola</h1>
        </div>

    
    @endauth

@guest
    <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
@endguest
@endsection

@section('scripts')
<!-- Referencia al archivo JS de manera directa -->
<script src="{{ asset('js/emptytrailer.js') }}"></script> <!-- Asegúrate que el archivo esté en public/js -->
@endsection



