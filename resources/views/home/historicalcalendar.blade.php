@extends('layouts.app-master')

@section('title', 'Historical Calendar View')

@section('content')
    @auth
    <div class="container  my-4">
        <div class="my-4 d-flex justify-content-center align-items-center">
            <h2 class="gradient-text text-capitalize fw-bolder" >Historical Calendar Viewer</h2>
        </div>
        <div class="container my-4">
            <!-- Centrar contenido horizontalmente -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <!-- Barra de búsqueda modificada -->
                    <div style="position: relative; display: inline-block; width: 100%;" class="me-4">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #6c757d; cursor: pointer;" onclick="document.getElementById('searchByStmId').focus()"></i>
                        <input type="text" id="searchByStmId" class="form-control" placeholder=" Search Historical Calendar" style="padding-left: 30px;">
                    </div>
                </div>
            </div>
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
@foreach ($shipments as $shipment)
<div id="shipmentModal{{ $shipment->stm_id }}" class="modal fade" tabindex="-1" aria-labelledby="shipmentModalLabel{{ $shipment->stm_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shipmentModalLabel{{ $shipment->stm_id }}">Shipment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <!-- Pestañas de detalle -->
               <ul class="nav nav-pills mb-3" id="pills-tab{{ $shipment->stm_id }}" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-shipment-details-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-shipment-details{{ $shipment->stm_id }}" role="tab" aria-controls="pills-shipment-details{{ $shipment->stm_id }}" aria-selected="true">Shipment Details</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-update-status-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-update-status{{ $shipment->stm_id }}" role="tab" aria-controls="pills-update-status{{ $shipment->stm_id }}" aria-selected="false">Offloading Menu</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent{{ $shipment->stm_id }}">
                    <!-- Shipment Details -->
                    <div class="tab-pane fade show active" id="pills-shipment-details{{ $shipment->stm_id }}" role="tabpanel" aria-labelledby="pills-shipment-details-tab{{ $shipment->stm_id }}">
                        <div class="mb-3">
                            <label class="form-label">STM ID</label>
                            <p>{{ $shipment->stm_id }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Landstar Reference</label>
                            <p>{{ $shipment->reference }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Origin</label>
                            <p>{{ $shipment->company->CoName ?? 'Origen no disponible' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Destination</label>
                            <p>{{ $shipment->destinationFacility->fac_name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <p>{{ $shipment->currentStatus->gntc_description ?? 'Unknown' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Approved ETA Date & Time</label>
                            <p>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i') : 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Units</label>
                            <p>{{ $shipment->units }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pallets</label>
                            <p>{{ $shipment->pallets }}</p>
                        </div>
                    </div>
                </div>

               <!-- Update Shipment Status -->
               <div class="tab-pane fade" id="pills-update-status{{ $shipment->stm_id }}" role="tabpanel" aria-labelledby="pills-update-status-tab{{ $shipment->stm_id }}">

                <form id="offloadingForm{{ $shipment->stm_id }}" method="POST" action="{{ route('update.status', ['pk_shipment' => $shipment->pk_shipment]) }}">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                    <label for="trailerId{{ $shipment->stm_id }}" class="form-label">Trailer ID</label>
                    <input type="text" class="form-control" id="trailerId{{ $shipment->stm_id }}" name="id_trailer" value="{{ $shipment->id_trailer ?? '' }}" readonly>
                    </div>
                    <div class="mb-3">
                    <label for="currentStatus{{ $shipment->stm_id }}" class="form-label">Current Status</label>
                    <select class="form-select" id="currentStatus{{ $shipment->stm_id }}" name="gnct_id_current_status">
                        @foreach($statusCatalog as $status)
                        <option value="{{ $status->gnct_id }}" {{ $status->gnct_id == old('gnct_id_current_status', $shipment->gnct_id_current_status) ? 'selected' : '' }}>
                            {{ $status->gntc_value }}
                        </option>
                        @endforeach
                    </select>
                    </div>
                    <div class="mb-3">
                    <label for="deliveredDate{{ $shipment->stm_id }}" class="form-label">Delivered Date</label>
                    <input type="datetime-local" class="form-control" id="deliveredDate{{ $shipment->stm_id }}" name="delivered_date" value="{{ old('delivered_date', $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="mb-3">
                    <label for="atDoorDate{{ $shipment->stm_id }}" class="form-label">At Door Date</label>
                    <input type="datetime-local" class="form-control" id="atDoorDate{{ $shipment->stm_id }}" name="at_door_date" value="{{ old('at_door_date', $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="offloadTime{{ $shipment->stm_id }}" class="form-label">Offload Time</label>
                        <input type="time" class="form-control" id="offloadTime{{ $shipment->stm_id }}" name="offloading_time" value="{{ old('offloading_time', $shipment->offloading_time ? \Carbon\Carbon::parse($shipment->offloading_time)->format('H:i') : '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="approvedETADateTime{{ $shipment->stm_id }}" class="form-label">Approved ETA Date & Time</label>
                        <input type="datetime-local" class="form-control" id="approvedETADateTime{{ $shipment->stm_id }}" name="wh_auth_date" value="{{ old('wh_auth_date', $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="saveButton{{ $shipment->stm_id }}">Save</button>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
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

              // Eliminar la hora
              eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: 'short',
                    hour12: true
                },

                // Modificar cómo se muestra el evento
                eventContent: function(info) {
                    // Suponiendo que el evento tiene una propiedad llamada 'shipmentInfo' que contiene la información del envío
                    return { html: '<strong>' + info.event.title + '</strong><br>'};
                },


            eventClick: function(info) {
                var event = info.event;
                var props = event.extendedProps;

                var modalTarget = "#shipmentModal" + props.stm_id;

                // Verificar si el modal existe en el DOM antes de intentar mostrarlo
                var modalElement = document.getElementById(modalTarget.substring(1)); // Eliminar el "#" para obtener el ID correcto
                if (modalElement) {
                    var modal = new bootstrap.Modal(modalElement); // Usar la API de Bootstrap para abrir el modal
                    modal.show();
                } else {
                    console.error("Modal no encontrado: " + modalTarget);
                }
            }
        });

        calendar.render();
        // Filtrar eventos por STM ID
        document.getElementById('searchByStmId').addEventListener('input', function (e) {
            const searchValue = e.target.value.toLowerCase();

            const filteredEvents = events.filter(event =>
                event.extendedProps.stm_id.toLowerCase().includes(searchValue)
            );

            calendar.removeAllEvents();
            calendar.addEventSource(filteredEvents);
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Delegación de eventos para formularios con id dinámico
        $(document).on('submit', 'form[id^="offloadingForm"]', function (event) {
            event.preventDefault(); // Evitar el envío estándar del formulario

            // Obtener la URL del formulario
            let formAction = $(this).attr('action');

            // Serializar los datos del formulario
            let formData = $(this).serialize();

            // Enviar los datos mediante AJAX
            $.ajax({
                url: formAction,
                method: 'PUT', // Asegúrate de que coincida con el método de tu ruta
                data: formData,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Enviando datos...',
                        text: 'Por favor espera mientras procesamos la información.',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading(); // Muestra un indicador de carga
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: response.message || 'El formulario se actualizó correctamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload(); // Recargar la página para ver los cambios
                    });
                    console.log(response);
                },
                error: function (xhr) {
                    let errorMessage = xhr.responseJSON?.message || 'Ocurrió un error al actualizar el envío.';
                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
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
    width: 100%; /* Ocupa todo el ancho de la pantalla */
    height: 100vh; /* Ocupa toda la altura de la ventana del navegador */
    margin: 0 auto;
}

.fc-daygrid-day {
    height: 120px; /* Ajusta la altura de las celdas de las fechas */
}

.fc-daygrid-day-number {
    line-height: 30px; /* Ajusta la alineación del número en cada celda */
}

.fc-daygrid-day-frame {
    padding: 5px; /* Puedes añadir un poco de espacio dentro de las celdas */
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

/* Estilo para las pestañas nav-pills */
.nav-pills .nav-link {
    font-weight: 600;
    background-color: #f8f9fa;
    color: #007bff;
    border: 1px solid #ddd;
    border-radius: 50px;
    transition: background-color 0.3s, color 0.3s;
}

/* Estilo para la pestaña activa */
.nav-pills .nav-link.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

/* Estilo para la pestaña cuando se pasa el cursor */
.nav-pills .nav-link:hover {
    background-color: #e2e6ea;
    color: #0056b3;
}

/* Estilo para el modal */
.modal-header {
    background-color: #007bff;
    color: white;
}

.modal-title {
    font-weight: 600;
}

.modal-footer .btn-secondary {
    background-color: #6c757d;
    color: white;
}

.modal-footer .btn-secondary:hover {
    background-color: #5a6268;
}

/* Estilo de contenido dentro de las pestañas */
.tab-content p {
    font-size: 14px;
    line-height: 1.6;
}
</style>



