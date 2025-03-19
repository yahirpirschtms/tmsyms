@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app-master')

@section('title', 'Dashboard')

@section('content')

    @auth
    <script>//almacenar trailers
        //let shipmentsData = @json($shipments->keyBy('pk_shipment'));
        //console.log(shipmentsData);
    </script>

    <style>
        .col-md-6 {
            display: flex;
            flex-direction: column;
        }

        .grow-1 {
            flex: 1;
        }

        .grow-2 {
            flex: 1.5;
        }

        /* Asegurar que las tarjetas llenen el espacio disponible */
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-body {
            flex-grow: 1; /* Permite que el contenido de la tarjeta crezca */
        }
    </style>

        <div id="encabezado y filtro" class="container  mt-4 " style=" background-color:white; position:fixed; left:0; right:0; top:80px; padding:10px; padding-bottom:0; z-index:10;" >
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" style="">Dashboard</h2>
            </div>
            <button id="recargar" style="display:none">Recargar</button>
        </div>

        <div class="container  mb-4" style="margin-top: 220px;">
            <div class="row">
                <!-- Primera columna con 3 divs -->
                <div class="col-md-6">
                    <div class="mb-4  grow-1 shadow-sm rounded">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color:#1e4877">EMPTY POOL</h5>
                                <div class="text-black">
                                    <hr>
                                </div>
                                <div class="container text-center mb-0 mt-0">
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW2</div>
                                        <div class="col" id="bw2_emptypool"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW3</div>
                                        <div class="col" id="bw3_emptypool"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Foxconn</div>
                                        <div class="col" id="foxconn_emptypool"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col" id="ontimeforwarding_emptypool"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Escoto</div>
                                        <div class="col" id="escoto_emptypool"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TFEMA Yard</div>
                                        <div class="col" id="tfemayard_emptypool"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNL Express</div>
                                        <div class="col" id="tnlexpress_emptypool"></div>
                                    </div>
                                    <div class="row align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNCH Yard</div>
                                        <div class="col" id="tnchyard_emptypool"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 grow-1 shadow-sm rounded">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color:#1e4877">PREALERTED</h5>
                                <div class="text-black">
                                    <hr>
                                </div>
                                <div class="container text-center mb-0 mt-0">
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW2</div>
                                        <div class="col" id="bw2_prealerted"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW3</div>
                                        <div class="col" id="bw3_prealerted"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Foxconn</div>
                                        <div class="col" id="foxconn_prealerted"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col" id="ontimeforwarding_prealerted"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Escoto</div>
                                        <div class="col" id="escoto_prealerted"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TFEMA Yard</div>
                                        <div class="col" id="tfemayard_prealerted"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNL Express</div>
                                        <div class="col" id="tnlexpress_prealerted"></div>
                                    </div>
                                    <div class="row align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNCH Yard</div>
                                        <div class="col" id="tnchyard_prealerted"></div>
                                    </div>
                                </div>
                            </div>
                            <!--<ul class="list-group list-group-flush">
                                <li class="list-group-item">An item</li>
                                <li class="list-group-item">A second item</li>
                                <li class="list-group-item">A third item</li>
                            </ul>-->
                            <!--<div class="card-body">
                                <a href="#" class="card-link">Card link</a>
                                <a href="#" class="card-link">Another link</a>
                            </div>-->
                        </div>
                    </div>
                    <div class="mb-4 grow-1 shadow-sm rounded">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color:#1e4877">DRIVER ASSIGNED</h5>
                                <div class="text-black">
                                    <hr>
                                </div>
                                <div class="container text-center mb-0 mt-0">
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW2</div>
                                        <div class="col" id="bw2_driverassigned"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW3</div>
                                        <div class="col" id="bw3_driverassigned"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Foxconn</div>
                                        <div class="col" id="foxconn_driverassigned"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col" id="ontimeforwarding_driverassigned"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Escoto</div>
                                        <div class="col" id="escoto_driverassigned"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TFEMA Yard</div>
                                        <div class="col" id="tfemayard_driverassigned"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNL Express</div>
                                        <div class="col" id="tnlexpress_driverassigned"></div>
                                    </div>
                                    <div class="row align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNCH Yard</div>
                                        <div class="col" id="tnchyard_driverassigned"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Segunda columna con 2 divs que ocupan todo el alto proporcionalmente -->
                <div class="col-md-6">
                    <div class="mb-4 grow-2 shadow rounded">
                        <div class="card border border-primary-subtle">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color:#1e4877">IN TRANSIT</h5>
                                <div class="text-black">
                                    <hr>
                                </div>

                                <div class="container text-center mb-0">
                                    <div class="row align-items-start mb-0 mt-0">
                                        <div class="col d-flex justify-content-start ms-5">
                                            <p class="fw-bold">FROM</p>
                                        </div>
                                        <div class="col d-flex justify-content-start">
                                            <p class="fw-bold">TO</p>
                                        </div>
                                        <div class="col">
                                            <p class="fw-bold">AMOUNT</p>
                                        </div>
                                    </div>
                                    <div class="mx-4" style="color:#1e4877">
                                        <hr>
                                    </div>
                                </div>
                                <div class="container text-start mb-0">
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW2</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col d-flex justify-content-center" id="bw2_intransit"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW3</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col d-flex justify-content-center" id="bw3_intransit"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Foxconn</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">Escoto</div>
                                        <div class="col d-flex justify-content-center" id="foxconn_intransit"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">TFEMA Yard</div>
                                        <div class="col d-flex justify-content-center" id="ontimeforwardingtfema_intransit"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">TNL Express</div>
                                        <div class="col d-flex justify-content-center" id="ontimeforwardingtnlexpress_intransit"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Escoto</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">TNCH Yard</div>
                                        <div class="col d-flex justify-content-center" id="escoto_intransit"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TFEMA Yard</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">K & N</div>
                                        <div class="col d-flex justify-content-center" id="tfema_intransit"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNL Express</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">K & N</div>
                                        <div class="col d-flex justify-content-center" id="tnlexpress_intransit"></div>
                                    </div>
                                    <div class="row align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNCH Yard</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">K & N</div>
                                        <div class="col d-flex justify-content-center" id="tnchyard_intransit"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 grow-2 shadow-sm rounded">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fw-bold" style="color:#1e4877">DELIVERED</h5>
                                <p id="dateDisplay" class="card-text" style="font-weight: 500;"></p>
                                <div class="text-black">
                                    <hr>
                                </div>
                                <div class="container text-center mt-0 mb-0">
                                    <div class="row align-items-center mb-0 mt-0">
                                        <div class="col d-flex justify-content-start ms-5">
                                            <p class="fw-bold">FROM</p>
                                        </div>
                                        <div class="col d-flex justify-content-start">
                                            <p class="fw-bold">TO</p>
                                        </div>
                                        <div class="col">
                                            <p class="fw-bold">AMOUNT</p>
                                        </div>
                                    </div>
                                    <div class="mx-4 my-0" style="color:#1e4877">
                                        <hr>
                                    </div>
                                </div>
                                <div class="container text-start mt-0 mb-0">
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW2</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col d-flex justify-content-center" id="bw2_delivered"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">BW3</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col d-flex justify-content-center" id="bw3_delivered"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Foxconn</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">Escoto</div>
                                        <div class="col d-flex justify-content-center" id="foxconn_delivered"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">TFEMA Yard</div>
                                        <div class="col d-flex justify-content-center" id="ontimeforwardingtfema_delivered"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">On Time Forwarding</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">TNL Express</div>
                                        <div class="col d-flex justify-content-center" id="ontimeforwardingtnlexpress_delivered"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">Escoto</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">TNCH Yard</div>
                                        <div class="col d-flex justify-content-center" id="escoto_delivered"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TFEMA Yard</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">K & N</div>
                                        <div class="col d-flex justify-content-center" id="tfema_delivered"></div>
                                    </div>
                                    <div class="row mb-2 align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNL Express</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">K & N</div>
                                        <div class="col d-flex justify-content-center" id="tnlexpress_delivered"></div>
                                    </div>
                                    <div class="row align-items-start">
                                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">TNCH Yard</div>
                                        <div class="col d-flex justify-content-start" style="font-weight: 600;">K & N</div>
                                        <div class="col d-flex justify-content-center" id="tnchyard_delivered"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($shipments as $status => $shipmentGroups)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-bold" style="color:#1e4877">
                {{ strtoupper($status) }} ({{ array_sum($shipmentCounts[$status] ?? []) }})
            </h5>
            <div class="text-black"><hr></div>
        </div>

        @foreach ($shipmentGroups as $origin => $shipmentsList)
            <div class="container">
                <h6 class="fw-bold text-primary">{{ strtoupper($origin) }} ({{ $shipmentCounts[$status][$origin] ?? 0 }})</h6>
                <div class="text-black"><hr></div>

                <div class="row text-center">
                    <div class="col fw-bold">FROM</div>
                    <div class="col fw-bold">TO</div>
                    <div class="col fw-bold">AMOUNT</div>
                </div>
                <div class="mx-4" style="color:#1e4877"><hr></div>

                @foreach ($shipmentsList as $shipment)
                    <div class="row text-center">
                        <div class="col">{{ $shipment->origins->CoName ?? 'N/A' }}</div>
                        <div class="col">{{ $shipment->destinations->CoName ?? 'N/A' }}</div>
                        <div class="col">14</div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endforeach

    
    @endauth

@guest
    <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
@endguest
@endsection

@section('scripts')
<!-- Referencia al archivo JS de manera directa -->
<script src="{{ asset('js/dashboard.js') }}"></script> <!-- Asegúrate que el archivo esté en public/js -->
@endsection



