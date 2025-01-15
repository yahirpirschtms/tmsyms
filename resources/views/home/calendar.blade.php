@extends('layouts.app-master')

@section('title', 'WH Appointment Viewer')

@section('content')
    @auth
        <div class="container  my-4">
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" >WH Appointment Viewer</h2>
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

    <!-- Offcanvas para mostrar los detalles del evento -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="eventOffcanvas" aria-labelledby="eventOffcanvasLabel">
    <div class="offcanvas-header">
      <h5 id="eventOffcanvasLabel">Event Details</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <h5 id="eventTitle">STM ID: <strong id="stm_id">12345</strong></h5>
      <p><strong>Landstar Reference:</strong> <strong id="reference">ABC123</strong></p>
      <p><strong>Origin:</strong> <span id="origin">{{ $events['origin'] ?? 'Unknown' }}</span></p>
      <p><strong>Destination:</strong> <strong id="destination">Los Angeles</strong></p>
      <p><strong>Current Status:</strong> <span id="current_status">{{ $events['current_status'] ?? 'Unknown' }}</span></p>
      <p><strong>Suggested Delivery Date:</strong> <strong id="suggested_delivery_date">01/20/2025 12:00 PM</strong></p>
      <p><strong>Approved ETA Date:</strong> <strong id="approved_eta_date">01/22/2025</strong></p>
      <p><strong>Approved ETA Time:</strong> <strong id="approved_eta_time">10:00 AM</strong></p>
      <p><strong>Units:</strong> <strong id="units">10</strong></p>
      <p><strong>Pallets:</strong> <strong id="pallets">5</strong></p>

      <!-- Botón para abrir el Offloading Menu -->
      <button type="button" class="btn btn-primary" id="openOffloadingMenuBtn" data-bs-toggle="collapse" data-bs-target="#offloadingMenu" aria-expanded="false" aria-controls="offloadingMenu">
        Offloading Menu
      </button>

      <!-- Offloading Menu (Collapse) -->
      <div class="collapse" id="offloadingMenu">
        <div class="card card-body mt-3">
            <form id="offloadingForm" method="POST" action="{{ route('update.status', ['pk_shipment' => $shipment->pk_shipment]) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                  <label for="trailerId" class="form-label">Trailer ID</label>
                  <input type="text" class="form-control" id="trailerId" name="id_trailer" value="{{ $shipment->id_trailer ?? '' }}" readonly>
                </div>
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
                <div class="mb-3">
                  <label for="deliveredDate" class="form-label">Delivered Date</label>
                  <input type="datetime-local" class="form-control" id="deliveredDate" name="delivered_date" value="{{ old('delivered_date', $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="mb-3">
                  <label for="atDoorDate" class="form-label">At Door Date</label>
                  <input type="datetime-local" class="form-control" id="atDoorDate" name="at_door_date" value="{{ old('at_door_date', $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="mb-3">
                  <label for="offloadDate" class="form-label">Offload Date</label>
                  <input type="datetime-local" class="form-control" id="offloadDate" name="offload_date" value="{{ old('offload_date', $shipment->offload_date ? \Carbon\Carbon::parse($shipment->offload_date)->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="mb-3">
                  <label for="approvedETADate" class="form-label">Approved ETA Date</label>
                  <input type="date" class="form-control" id="approvedETADate" name="approved_eta_date" value="{{ old('approved_eta_date', $shipment->approved_eta_date ? \Carbon\Carbon::parse($shipment->approved_eta_date)->format('Y-m-d') : '') }}">
                </div>
                <div class="mb-3">
                  <label for="approvedETATime" class="form-label">Approved ETA Time</label>
                  <input type="time" class="form-control" id="approvedETATime" name="approved_eta_time" value="{{ old('approved_eta_time', $shipment->approved_eta_time ? \Carbon\Carbon::parse($shipment->approved_eta_time)->format('H:i') : '') }}">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" id="saveButton">Save</button>
                </div>
              </form>
        </div>
      </div>
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
                var offcanvas = new bootstrap.Offcanvas(document.getElementById('eventOffcanvas'));
                offcanvas.show();

                // Guardar los datos del evento en un objeto para usarlos más tarde
                window.currentEventData = props; // Guardamos los datos para usarlos en el offcanvas
            }
        });

            calendar.render();
             // Cerrar el slider cuando se haga clic en el botón de cierre
            // Botón de Offlanding
            document.getElementById('openOffloadingMenuBtn').addEventListener('click', function() {
            var eventData = window.currentEventData; // Obtener los datos del evento guardados

            if (eventData) { // Validar que los datos existan antes de abrir
                var offcanvas = new bootstrap.Offcanvas(document.getElementById('offloadingOffcanvas'));
                offcanvas.show();
            } else {
                console.error('No hay datos del evento disponibles para cargar en el offcanvas.');
            }
            });


        });



    </script>

<script>

$(document).ready(function () {
            // Interceptar el envío del formulario
            $('#offloadingForm').on('submit', function (event) {
                event.preventDefault(); // Evitar el envío estándar del formulario

                // Obtener la URL del formulario
                let formAction = $(this).attr('action');

                // Serializar los datos del formulario
                let formData = $(this).serialize();

                // Enviar los datos mediante AJAX
                $.ajax({
                    url: formAction,
                    method: 'PUT',
                    data: formData,
                    beforeSend: function () {
                        // Puedes agregar un indicador de carga aquí si lo necesitas
                        console.log('Enviando datos...');
                    },
                    success: function (response) {
                        // Manejar la respuesta exitosa
                        alert(response.message);
                        console.log(response);

                        // Actualizar la página o realizar alguna acción adicional
                        location.reload(); // Recargar la página para ver los cambios
                    },
                    error: function (xhr) {
                        // Manejar errores
                        let errorMessage = xhr.responseJSON?.message || 'Ocurrió un error al actualizar el envío.';
                        alert(errorMessage);
                        console.error(xhr.responseJSON?.error || xhr.responseText);
                    },
                });
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
</style>

