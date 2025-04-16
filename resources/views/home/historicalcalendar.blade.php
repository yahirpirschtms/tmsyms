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
                <div class="modal-header" style="background-color: #0056b3;">
                    <h5 class="modal-title" id="shipmentModalLabel{{ $shipment->stm_id }}">Shipment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Pestañas de detalle -->
                    <ul class="nav nav-pills mb-3" id="pills-tab{{ $shipment->stm_id }}" role="tablist">
                        <li class="nav-item me-2" role="presentation">
                            <a class="nav-link active" id="pills-shipment-details-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-shipment-details{{ $shipment->stm_id }}" role="tab" aria-controls="pills-shipment-details{{ $shipment->stm_id }}" aria-selected="true">Shipment Details</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-update-status-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-update-status{{ $shipment->stm_id }}" role="tab" aria-controls="pills-update-status{{ $shipment->stm_id }}" aria-selected="false">Offloading</a>
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
                                <label class="form-label">Carrier Reference</label>
                                <p>{{ $shipment->reference }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Origin</label>
                                <p>{{ $shipment->company->CoName ?? 'Origen no disponible' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Destination</label>
                                <p>{{ optional($companies->firstWhere('pk_company', $shipment->destination))->CoName ?? 'Not available' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Current Status</label>
                                <p>{{ $shipment->currentStatus->gntc_description ?? 'Unknown' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Suggested Delivery Date</label>
                                <p>{{ \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Approved ETA Date & Time</label>
                                <p>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i') : 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Door Number</label>
                                <p>{{ $shipment->door_number ? $shipment->door_number : 'N/A' }}</p>
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

                        <!-- Update Shipment Status (Offloading Menu) -->
                        <div class="tab-pane fade" id="pills-update-status{{ $shipment->stm_id }}" role="tabpanel" aria-labelledby="pills-update-status-tab{{ $shipment->stm_id }}">
                            @if ($shipment)
                            <form id="offloadingForm{{ $shipment->stm_id }}" method="POST" action="{{ route('update.status', ['pk_shipment' => $shipment->pk_shipment]) }}">
                                @method('PUT')
                                @csrf
                                <div class="mb-3">
                                    <label for="trailerId{{ $shipment->stm_id }}" class="form-label">Trailer ID</label>
                                    <input type="text" class="form-control" id="trailerId{{ $shipment->stm_id }}" name="id_trailer" value="{{ $shipment->id_trailer ?? '' }}" readonly data-original="{{ $shipment->id_trailer ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="currentStatus{{ $shipment->stm_id }}" class="form-label">Current Status</label>
                                    <select class="form-select" id="currentStatus-{{ $shipment->stm_id }}" name="gnct_id_current_status" data-original="{{ $shipment->gnct_id_current_status }}">
                                        @foreach ($currentStatus as $status)
                                            <option value="{{ $status->gnct_id }}"
                                                {{ old('gnct_id_current_status', $shipment->gnct_id_current_status) == $status->gnct_id ? 'selected' : '' }}>
                                                {{ $status->gntc_description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="deliveredDate{{ $shipment->stm_id }}" class="form-label">Delivered Date</label>
                                    <input type="text"
                                        class="form-control datetime-picker"
                                        id="deliveredDate{{ $shipment->stm_id }}"
                                        name="delivered_date"
                                        value="{{ $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="{{ $shipment->delivered_date ? '' : 'mm/dd/yyyy --:--' }}"
                                        data-original="{{ $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('deliveredDate{{ $shipment->stm_id }}', 'Delivered', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="atDoorDate{{ $shipment->stm_id }}" class="form-label">At Door Date</label>
                                    <input type="text" class="form-control datetime-picker" id="atDoorDate{{ $shipment->stm_id }}" name="at_door_date"
                                        value="{{ old('at_door_date', $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('m/d/Y H:i') : '') }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ old('at_door_date', $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('m/d/Y H:i') : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="doorNumber{{ $shipment->stm_id }}" class="form-label">Door Number</label>
                                    <select class="form-select" id="doorNumber{{ $shipment->stm_id }}" name="door_number" data-original="{{ $shipment->door_number ?? '' }}">
                                        <option value="">Select a Door</option>
                                        @foreach ($doorNumbers as $door)
                                            <option value="{{ $door->gntc_description }}" {{ old('door_number', $shipment->door_number) == $door->gntc_description ? 'selected' : '' }}>
                                                {{ $door->gntc_description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="offloadTime{{ $shipment->stm_id }}" class="form-label">Offload Time</label>
                                    <input type="time" class="form-control" id="offloadTime{{ $shipment->stm_id }}" name="offloading_time"
                                        value="{{ old('offloading_time', $shipment->offloading_time ? \Carbon\Carbon::parse($shipment->offloading_time)->format('H:i') : '') }}"
                                        data-original="{{ old('offloading_time', $shipment->offloading_time ? \Carbon\Carbon::parse($shipment->offloading_time)->format('H:i') : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="approvedETADateTime{{ $shipment->stm_id }}" class="form-label">Approved ETA Date & Time</label>
                                    <input type="text" class="form-control datetime-picker" id="approvedETADateTime{{ $shipment->stm_id }}" name="wh_auth_date"
                                        value="{{ old('wh_auth_date', $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i') : '') }}"
                                        data-original="{{ old('wh_auth_date', $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i') : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="removedTrackers{{ $shipment->stm_id }}" class="form-label">Removed Trackers?</label>
                                    <select class="form-select" id="removedTrackers{{ $shipment->stm_id }}" name="removed_trackers" required data-original="{{ $shipment->removed_trackers }}">
                                        <option value="" disabled selected>Select an option</option>
                                        <option value="Yes" {{ old('removed_trackers', $shipment->removed_trackers) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('removed_trackers', $shipment->removed_trackers) == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="saveButton{{ $shipment->stm_id }}">Save</button>
                                </div>
                            </form>
                            @else
                                <p>No hay envíos disponibles actualmente.</p>
                            @endif
                        </div>
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

<script>
    function checkAndChangeStatus(dateFieldId, statusDescription, shipmentId) {
        const dateField = document.getElementById(dateFieldId);

        if (!dateField) {
            console.error(`No se encontró el campo con id: ${dateFieldId}`);
            return; // Si el campo no existe, no continuar con el cambio de estado
        }

        const currentDateValue = dateField.value;

        // Verificar si ya existe una fecha en el campo y evitar el cambio de estado
        if (currentDateValue) {
            console.log(`El campo ${dateFieldId} ya tiene una fecha, no se cambiará el estado.`);
            return; // Si ya hay una fecha, no cambiar el estado
        }

        // Cambiar el estado solo si el campo está vacío, usando la descripción
        changeStatusByDescription(statusDescription, shipmentId);
    }

    function changeStatusByDescription(statusDescription, shipmentId) {
        console.log('shipmentId recibido:', shipmentId); // Verifica el valor de shipmentId
        console.log('Estado recibido:', statusDescription); // Verifica la descripción del estado

        const statusMapping = {
            'Delivered': 'Delivered', // gntc_description
            // Agrega otras descripciones si es necesario
        };

        const gntcDescription = statusMapping[statusDescription];

        if (gntcDescription) {
            const statusSelect = document.getElementById('currentStatus-' + shipmentId);
            if (statusSelect) {
                // Buscar el option que tenga el gntc_description correspondiente
                const options = statusSelect.getElementsByTagName('option');
                for (let i = 0; i < options.length; i++) {
                    if (options[i].textContent.trim() === gntcDescription) {
                        statusSelect.value = options[i].value; // Establecer el valor del select según el texto de la opción
                        console.log('Estado cambiado a:', gntcDescription);
                        break;
                    }
                }
            } else {
                console.error('No se encontró el select para el envío:', shipmentId);
            }
        } else {
            console.error('Descripción de estado no mapeada:', statusDescription);
        }
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".datetime-picker", {
            enableTime: true,        // Permite seleccionar hora
            dateFormat: "m/d/Y H:i", // Formato M/D/Y H:i
            time_24hr: false,        // Usa formato de 12 horas (AM/PM)
            allowInput: true,        // Permite escribir la fecha manualmente
            onOpen: function(selectedDates, dateStr, instance) {
                // Si el campo está vacío, se coloca la fecha y hora actual
                if (dateStr === "") {
                    instance.setDate(new Date(), true); // Establece la fecha y hora actuales
                }
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Seleccionamos todos los modales que tienen el prefijo 'shipmentModal' en su ID
        const shipmentModals = document.querySelectorAll("[id^='shipmentModal']");

        shipmentModals.forEach(shipmentModal => {
            // Al abrir el modal, guardar los valores actuales de los campos de fecha y otros
            shipmentModal.addEventListener("shown.bs.modal", function () {
                const dateInputs = shipmentModal.querySelectorAll("input[type='date'], input[type='datetime-local'], .flatpickr");
                const selectInputs = shipmentModal.querySelectorAll("select");

                dateInputs.forEach(input => {
                    if (input.value) {
                        input.dataset.original = input.value; // Guardar el valor en el dataset original
                    } else {
                        input.dataset.original = ''; // Si está vacío, también guardamos ese estado
                    }
                });

                selectInputs.forEach(select => {
                    if (select.value) {
                        select.dataset.original = select.value; // Guardar el valor en el dataset original
                    } else {
                        select.dataset.original = ''; // Si está vacío, también guardamos ese estado
                    }
                });
            });

            // Al cerrar el modal, restaurar los valores originales
            shipmentModal.addEventListener("hidden.bs.modal", function () {
                const inputs = shipmentModal.querySelectorAll("input, select, textarea, .flatpickr");
                inputs.forEach(input => {
                    if (input.dataset.original !== undefined) {
                        input.value = input.dataset.original; // Restaurar el valor original
                    }
                });
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

.fc-toolbar-title{
    color: #0056b3;
    font-weight: bolder;
}
/* Día actual en la vista de cuadrícula (mes) */
.fc-daygrid-day.fc-day-today {
    background-color: #dde5f4 !important;
}

/* Estilo base para todos los botones */
.fc-button {
    background-color: #0056b3 !important; /* Color de fondo (ejemplo: verde) */
    color: white !important; /* Color del texto */
    border: none !important; /* Eliminar el borde */
    border-radius: 5px; /* Bordes redondeados */
}

/* Cambiar el color cuando el botón está activo */
.fc-button-primary {
    background-color: #0056b3 !important; /* Un verde más oscuro */
}
/* Estilo para botones activos (sin borde negro) */
.fc-button.fc-button-active,
.fc-button:active {
    outline: none !important; /* Elimina el borde por defecto */
    box-shadow: none !important; /* Elimina la sombra */
}

/* Cambiar el color al pasar el mouse */
.fc-button:hover {
    background-color: #3372bf !important; /* Un verde más claro */
    color: #fff !important;
}

/* Eliminar la opacidad de los eventos en la vista de semana */
.fc-timegrid-event {
    opacity: 1 !important; /* Elimina la opacidad */
    background-color: darkorange !important; /* Un fondo verde brillante */
    border: none !important;
}

/* Día actual en las vistas de semana y día */
.fc-timegrid-col.fc-day-today {
    background-color: #dde5f4 !important;
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
/*.fc-timegrid-slot .fc-timegrid-slot-lane {
    background-color: white;
}*/
.fc-col-header-cell-cushion{
    text-decoration: none;
    color: #0056b3;
}
.fc-daygrid-day-number{
    text-decoration: none;
    color: #0056b3;
}
.fc-event {
    background-color: darkorange;
    color: white;
    border-radius: .375rem;
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
    background-color: #3372bf;
}

/* Estilo para las pestañas nav-pills */

.nav-tabs {
    font-weight: 600;
    background-color: #f8f9fa;
    color: #007bff;
    border: 1px solid #ddd;
    border-radius: 50px;
    transition: background-color 0.3s, color 0.3s;
    display: flex;
    justify-content: center;
    flex-wrap: wrap; /* Esto permite que las pestañas se ajusten en pantallas más pequeñas */
}

/* Pestañas inactivas con texto oscuro */
.nav-pills .nav-link {
    font-weight: 600;
    background-color: #f8f9fa;
    color: #007bff;
    border: 1px solid #ddd;
    border-radius: 50px;
    margin: 0 10px 10px 10px; /* Separación horizontal y vertical */
    padding: 10px 15px;
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

/* Media query para pantallas móviles */
@media (max-width: 576px) {
    .nav-tabs {
        flex-direction: column; /* Hace que las pestañas se alineen verticalmente */
        align-items: center; /* Centra las pestañas en la pantalla */
    }

    .nav-pills .nav-link {
        margin: 5px 0; /* Espaciado vertical en pantallas pequeñas */
    }
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
.fc-button{
    background-color: #0056b3;
}
</style>



