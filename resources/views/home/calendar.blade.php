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
        <!-- Contenido del Calendario -->
    <div class="container-fluid" style="margin-top: 100px;">
        <div class="row">
            <div class="col-12">

                <!-- Calendario interactivo -->
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Slider lateral para mostrar los detalles del evento -->
    <div id="eventSlider" class="event-slider">
        <div class="slider-content">
            <button id="closeSliderBtn" class="btn-close" aria-label="Close" type="button">×</button>

            <h5 id="eventTitle" class="slider-title">STM ID: <strong id="stm_id">12345</strong></h5>
            <p><strong>Landstar Reference:</strong> <strong id="reference">ABC123</strong></p>
            <p><strong>Origin:</strong> <span id="origin">{{ $events['origin'] ?? 'Unknown' }}</span></p>
            <p><strong>Destination:</strong> <strong id="destination">Los Angeles</strong></p>
            <p><strong>Current Status:</strong> <span id="current_status">{{ $events['current_status'] ?? 'Unknown' }}</span></p>
            <p><strong>Suggested Delivery Date:</strong> <strong id="suggested_delivery_date">01/20/2025 12:00 PM</strong></p>
            <p><strong>Approved ETA Date:</strong> <strong id="approved_eta_date">01/22/2025</strong></p>
            <p><strong>Approved ETA Time:</strong> <strong id="approved_eta_time">10:00 AM</strong></p>
            <p><strong>Units:</strong> <strong id="units">10</strong></p>
            <p><strong>Pallets:</strong> <strong id="pallets">5</strong></p>

            <button type="button" class="btn btn-primary" id="openOffloadingMenuBtn">Offloading Menu</button>
        </div>
    </div>


                <!-- Contenedor del Offcanvas -->
        <!-- Contenedor del Offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offloadingOffcanvas" aria-labelledby="offloadingOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 id="offloadingOffcanvasLabel">Offloading Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Formulario del Offloading -->
                <form id="offloadingForm" method="POST" action="{{ route('update.offloadingStatus', ['pk_shipment' => $shipment->pk_shipment]) }}">
                    @csrf
                    @method('PUT')

                    <!-- Trailer ID -->
                    <div class="mb-3">
                        <label for="trailerId" class="form-label">Trailer ID</label>
                        <input type="text" class="form-control" id="trailerId" name="id_trailer" value="{{ $shipment->id_trailer ?? '' }}" readonly>
                    </div>

                    <!-- STM ID (desactivado) -->
                    <div class="mb-3">
                        <label for="stmId" class="form-label">STM ID</label>
                        <input type="text" class="form-control" id="stmId" name="stm_id" disabled value="{{ $shipment->stm_id }}">
                    </div>

                    <!-- Current Status -->
                    <div class="mb-3">
                        <label for="currentStatus" class="form-label">Current Status</label>
                        <select class="form-select" id="currentStatus" name="gnct_id_current_status">
                            @foreach($statusCatalog as $status)
                                <option value="{{ $status->gnct_id }}" {{ $status->gnct_id == old('gnct_id_current_status', $shipment->gnct_id_current_status) ? 'selected' : '' }}>
                                    {{ $status->gntc_value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Delivered/Received Date -->
                    <div class="mb-3">
                        <label for="deliveredDate" class="form-label">Delivered Date</label>
                        <input type="datetime-local" class="form-control" id="deliveredDate" name="delivered_date"
                            value="{{ old('delivered_date', $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="atDoorDate" class="form-label">At Door Date</label>
                        <input type="datetime-local" class="form-control" id="atDoorDate" name="at_door_date"
                            value="{{ old('at_door_date', $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="offloadDate" class="form-label">Offload Date</label>
                        <input type="datetime-local" class="form-control" id="offloadDate" name="offload_date"
                            value="{{ old('offload_date', $shipment->offload_date ? \Carbon\Carbon::parse($shipment->offload_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="approvedETADate" class="form-label">Approved ETA Date</label>
                        <input type="date" class="form-control" id="approvedETADate" name="approved_eta_date"
                            value="{{ old('approved_eta_date', $shipment->approved_eta_date ? \Carbon\Carbon::parse($shipment->approved_eta_date)->format('Y-m-d') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="approvedETATime" class="form-label">Approved ETA Time</label>
                        <input type="time" class="form-control" id="approvedETATime" name="approved_eta_time"
                            value="{{ old('approved_eta_time', $shipment->approved_eta_time ? \Carbon\Carbon::parse($shipment->approved_eta_time)->format('H:i') : '') }}">
                    </div>

                    <!-- Botón de guardar -->
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </form>
            </div>
        </div>
</div>
    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection

@section('scripts')
    <script>
        // Pasamos los catálogos de origen y estado al frontend
        var originCatalog = @json($originCatalog);
        var statusCatalog = @json($statusCatalog);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    // Este código sigue funcionando para cargar los eventos en el calendario
    const events = {!! json_encode($events) !!};
    console.log(events);

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridDay',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth'
        },
        events: events,

        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short',
            hour12: true
        },

        eventClick: function(info) {
            var event = info.event;
            var props = event.extendedProps;

            // Actualizar el contenido del slider con los datos del evento
            document.getElementById('stm_id').innerText = props.stm_id || 'N/A';
            document.getElementById('reference').innerText = props.reference || 'N/A';
            document.getElementById('origin').innerText = props.origin || 'Unknown';
            document.getElementById('destination').innerText = props.destination || 'N/A';
            document.getElementById('current_status').innerText = props.current_status || 'Unknown';
            document.getElementById('suggested_delivery_date').innerText = props.suggested_delivery_date || 'N/A';
            document.getElementById('approved_eta_date').innerText = props.approved_eta_date || 'N/A';
            document.getElementById('approved_eta_time').innerText = props.approved_eta_time || 'N/A';
            document.getElementById('units').innerText = props.units || 'N/A';
            document.getElementById('pallets').innerText = props.pallets || 'N/A';

            // Mostrar el slider
            document.getElementById('eventSlider').classList.add('show');

            // Guardar los datos del evento en un objeto para usarlos más tarde
            window.currentEventData = props; // Guardamos los datos para usarlos en el offcanvas
        }
    });

            calendar.render();

            document.getElementById('openOffloadingMenuBtn').addEventListener('click', function() {
                var eventData = window.currentEventData; // Obtener los datos del evento guardados
                console.log("Event Data:", eventData);  // Verificar la estructura de eventData

                // Mostrar el offcanvas para editar
                var offcanvas = new bootstrap.Offcanvas(document.getElementById('offloadingOffcanvas'));
                offcanvas.show();
            });
        });
    </script>


@endsection

@section('custom-css')
<style>
            #calendar {
            max-width: 80%;
            margin: 0 auto;
            height: 700px;
        }

        .fc-event {
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            padding: 10px;
            font-size: 12px;
        }

        .fc-event-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .fc-event-description {
            font-size: 10px;
        }

        .fc-event:hover {
            background-color: #45a049;
        }

        /* Estilo del slider lateral */
        .event-slider {
            position: fixed;
            top: 0;
            right: -100%; /* Inicialmente oculto a la derecha */
            width: 400px;
            height: 100%;
            background-color: #f8f9fa;
            transition: right 0.3s ease;
            z-index: 1000;
            box-shadow: -2px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .slider-content {
            overflow-y: auto;
            flex-grow: 1;
        }

        .slider-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .slider-description {
            font-size: 1rem;
            color: #333;
        }

        /* Mover el botón de cierre a la derecha */
        .btn-close {
            background: transparent;
            border: none;
            font-size: 2rem;
            color: #6c757d;
            padding: 0;
            margin: 0;
            position: absolute;
            top: 20px;
            right: 20px; /* Alineación a la derecha */
        }

        .btn-close:hover {
            color: #dc3545;
        }

        .btn {
            margin-top: 20px;
        }

        #offLandingMenuBtn {
            background-color: #1e4877;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
        }

        #offLandingMenuBtn:hover {
            background-color: #155a7f;
        }

        #offLandingMenuBtn i {
            margin-right: 5px;
        }

        /* Mostrar el slider */
        .event-slider.show {
            right: 0;
        }

        /* Estilo para asegurar el color blanco en el texto de la barra de navegación */
        .navbar-dark .navbar-nav .nav-link {
            color: #ffffff !important; /* Asegura el color blanco en los enlaces */
        }

        .navbar-dark .navbar-brand {
            color: #ffffff !important; /* Asegura que el texto de la marca también sea blanco */
        }

        .navbar-dark .navbar-toggler-icon {
            background-color: #ffffff; /* Cambiar el color del ícono del toggler a blanco */
        }

        .offcanvas {
        max-width: 500px; /* Ajusta el ancho del Offcanvas */
        }
</style>



