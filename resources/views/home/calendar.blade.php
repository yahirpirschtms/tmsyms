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
                                <p>{{ $shipment->originCatalog->gntc_value ?? 'Origen no disponible' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Destination</label>
                                <p>{{ $shipment->destination }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Current Status</label>
                                <p>{{ $shipment->currentStatus->gntc_description ?? 'Unknown' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Suggested Delivery Date</label>
                                <p>{{ $shipment->suggested_delivery_date }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Approved ETA Date</label>
                                <p>{{ $shipment->approved_eta_date }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Approved ETA Time</label>
                                <p>{{ $shipment->approved_eta_time }}</p>
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
                        <label for="offloadDate{{ $shipment->stm_id }}" class="form-label">Offload Date</label>
                        <input type="datetime-local" class="form-control" id="offloadDate{{ $shipment->stm_id }}" name="offload_date" value="{{ old('offload_date', $shipment->offload_date ? \Carbon\Carbon::parse($shipment->offload_date)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        <div class="mb-3">
                        <label for="approvedETADate{{ $shipment->stm_id }}" class="form-label">Approved ETA Date</label>
                        <input type="date" class="form-control" id="approvedETADate{{ $shipment->stm_id }}" name="approved_eta_date" value="{{ old('approved_eta_date', $shipment->approved_eta_date ? \Carbon\Carbon::parse($shipment->approved_eta_date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="mb-3">
                        <label for="approvedETATime{{ $shipment->stm_id }}" class="form-label">Approved ETA Time</label>
                        <input type="time" class="form-control" id="approvedETATime{{ $shipment->stm_id }}" name="approved_eta_time" value="{{ old('approved_eta_time', $shipment->approved_eta_time ? \Carbon\Carbon::parse($shipment->approved_eta_time)->format('H:i') : '') }}">
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

        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short',
            hour12: true
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

