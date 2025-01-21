@extends('layouts.app-master')

@section('title', 'All Shipments')

@section('content')
    @auth
    <div class="container my-4">
        <!-- Título centrado -->
        <div class="d-flex justify-content-center my-4">
            <h2 class="gradient-text text-capitalize fw-bolder">All Shipments</h2>
        </div>
        <div class="container my-4">
            <!-- Centrar contenido horizontalmente -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <!-- Barra de búsqueda -->
                    <input type="text" id="searchByShipment" class="form-control" placeholder="Search all shipments">
                </div>
            </div>
        </div>


            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Shipment Type</th>
                            <th>STM ID</th>
                            <th>Secondary Shipment ID</th>
                            <th>Landstar Reference</th>
                            <th>Origin</th>
                            <th>Trailer ID</th>
                            <th>Destination</th>
                            <th>Pre-Alert Date & Time</th>
                            <th>Carrier Dropping Trailer</th>
                            <th>Trailer Owner</th>
                            <th>Driver & Truck</th>
                            <th>Suggested Delivery Date</th>
                            <th>Units</th>
                            <th>Pallets</th>
                            <th>Security Seals</th>
                            <th>Notes</th>
                            <th>Current Status</th>
                            <th>Driver Assigned Date</th>
                            <th>Picked Up Date</th>
                            <th>In Transit Date</th>
                            <th>Delivered/Received Date</th>
                            <th>Secured Yard Date</th>
                            <th>Approved ETA Date & Time</th>
                            <th>Sec Incident</th>
                            <th>Incident Type</th>
                            <th>Incident Date</th>
                            <th>Incident Notes</th>
                            <th>WH Status</th>
                            <th>At Door Time</th>
                            <th>Offload Time</th>
                            <th>Date of Billing</th>
                            <th>Billing ID</th>
                            <th>Device Number</th>
                            <th>Overhaul ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                        <tr data-bs-toggle="modal" data-bs-target="#shipmentModal{{ $shipment->stm_id }}" class="clickable-row" data-shipment-id="{{ $shipment->stm_id }}">
                            <td>{{ $shipment->gnct_id_shipment_type }}</td>
                            <td>{{ $shipment->stm_id }}</td>
                            <td>{{ $shipment->secondary_shipment_id }}</td>
                            <td>{{ $shipment->reference }}</td>
                            <td>{{ $shipment->originCatalog->gntc_value ?? 'Origen no disponible' }}</td>
                            <td>{{ $shipment->id_trailer }}</td>
                            <td>{{ $shipment->destination }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') ?? 'No disponible' }}</td>
                            <td>{{ $shipment->carrier_dropping_trailer }}</td>
                            <td>{{ $shipment->trailer_owner }}</td>
                            <td>{{ $shipment->driver_truck }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y') }}</td>
                            <td>{{ $shipment->units }}</td>
                            <td>{{ $shipment->pallets }}</td>
                            <td>{{ $shipment->security_seals }}</td>
                            <td>{{ $shipment->notes }}</td>
                            <td>{{ $shipment->currentStatus->gntc_value ?? 'Estado no disponible' }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y') }}</td>
                            <td>{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i') : 'No disponible' }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i:s') }}</td>

                            <td>{{ $shipment->sec_incident }}</td>
                            <td>{{ $shipment->incident_type }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->incident_date)->format('m/d/Y') }}</td>
                            <td>{{ $shipment->incident_notes }}</td>
                            <td>{{ $shipment->wh_status }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->at_door_date)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->offload_date)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->billing_date)->format('m/d/Y') }}</td>
                            <td>{{ $shipment->billing_id }}</td>
                            <td>{{ $shipment->device_number }}</td>
                            <td>{{ $shipment->overhaul_id }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para ver los detalles del envío -->
        @foreach ($shipments as $shipment)
        <div class="modal fade" id="shipmentModal{{ $shipment->stm_id }}" tabindex="-1" aria-labelledby="shipmentModalLabel{{ $shipment->stm_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shipmentModalLabel{{ $shipment->stm_id }}">Shipment Details - {{ $shipment->stm_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Pestañas de detalle -->
                        <ul class="nav nav-pills mb-3" id="pills-tab{{ $shipment->stm_id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-shipment-details-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-shipment-details{{ $shipment->stm_id }}" role="tab" aria-controls="pills-shipment-details{{ $shipment->stm_id }}" aria-selected="true">Shipment Details</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-update-status-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-update-status{{ $shipment->stm_id }}" role="tab" aria-controls="pills-update-status{{ $shipment->stm_id }}" aria-selected="false">Initial Shipment Info</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="notes-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#notes{{ $shipment->stm_id }}" role="tab" aria-controls="notes{{ $shipment->stm_id }}" aria-selected="false">Update Shipment Status</a>
                            </li>
                        </ul>
                        </ul>
                        <div class="tab-content" id="pills-tabContent{{ $shipment->stm_id }}">
                            <!-- Shipment Details -->
                            <div class="tab-pane fade show active" id="pills-shipment-details" role="tabpanel" aria-labelledby="pills-shipment-details-tab">
                                <div class="mb-3">
                                    <label class="form-label">STM ID</label>
                                    <p>{{ $shipment->stm_id }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reference</label>
                                    <p>{{ $shipment->reference }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bonded</label>
                                    <p>{{ $shipment->bonded }}</p>
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
                                    <label class="form-label">Pre-Alerted Date & Time</label>
                                    <p>{{ \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i:s') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trailer ID</label>
                                    <p>{{ $shipment->id_trailer }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Company ID</label>
                                    <p>{{ $shipment->id_company }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trailer</label>
                                    <p>{{ $shipment->trailer }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Truck</label>
                                    <p>{{ $shipment->truck }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Driver ID</label>
                                    <p>{{ $shipment->id_driver }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ETD (Estimated Time of Departure)</label>
                                    <p>{{ \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i:s') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Units</label>
                                    <p>{{ $shipment->units }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pallets</label>
                                    <p>{{ $shipment->pallets }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Security Seals</label>
                                    <p>{{ $shipment->security_seals }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Overhaul ID</label>
                                    <p>{{ $shipment->overhaul_id }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Device Number</label>
                                    <p>{{ $shipment->device_number }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secondary Shipment ID</label>
                                    <p>{{ $shipment->secondary_shipment_id }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Driver Assigned Date</label>
                                    <p>{{ \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i:s') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pick-Up Date</label>
                                    <p>{{ \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i:s') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">In Transit Date</label>
                                    <p>{{ \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i:s') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secured Yard Date</label>
                                    <p>{{ \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i:s') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Current Status</label>
                                    <p>{{ $shipment->currentStatus->gntc_description ?? 'Unknown' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Shipment Type (GNCT ID)</label>
                                    <p>{{ $shipment->gnct_id_shipment_type }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Delivered Date</label>
                                    <p>{{ \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y H:i:s') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">At Door Date</label>
                                    <p>{{ \Carbon\Carbon::parse($shipment->at_door_date)->format('m/d/Y H:i:s') }}</p>
                                </div>
                            </div>
                            <!-- Update Shipment Status -->
                            <div class="tab-pane fade" id="pills-update-status{{ $shipment->stm_id }}" role="tabpanel" aria-labelledby="pills-update-status-tab{{ $shipment->stm_id }}">
                                <form>
                                    <div class="mb-3">
                                        <label for="stm_id" class="form-label">STM ID</label>
                                        <input type="text" class="form-control" id="stm_id" value="{{ $shipment->stm_id }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="reference" class="form-label">Reference</label>
                                        <input type="text" class="form-control" id="reference" value="{{ $shipment->reference }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bonded" class="form-label">Bonded</label>
                                        <input type="text" class="form-control" id="bonded" value="{{ $shipment->bonded }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="origin" class="form-label">Origin</label>
                                        <input type="text" class="form-control" id="origin" value="{{ $shipment->originCatalog->gntc_value ?? 'Origen no disponible' }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="destination" class="form-label">Destination</label>
                                        <input type="text" class="form-control" id="destination" value="{{ $shipment->destination }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="pre_alerted_datetime" class="form-label">Pre-Alert Date & Time</label>
                                        <input type="text" class="form-control" id="pre_alerted_datetime" value="{{ $shipment->pre_alerted_datetime }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_trailer" class="form-label">Trailer ID</label>
                                        <input type="text" class="form-control" id="id_trailer" value="{{ $shipment->id_trailer }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_company" class="form-label">Company ID</label>
                                        <input type="text" class="form-control" id="id_company" value="{{ $shipment->id_company }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="trailer" class="form-label">Trailer</label>
                                        <input type="text" class="form-control" id="trailer" value="{{ $shipment->trailer }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="truck" class="form-label">Truck</label>
                                        <input type="text" class="form-control" id="truck" value="{{ $shipment->truck }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_driver" class="form-label">Driver ID</label>
                                        <input type="text" class="form-control" id="id_driver" value="{{ $shipment->id_driver }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="etd" class="form-label">ETD (Estimated Time of Departure)</label>
                                        <input type="text" class="form-control" id="etd" value="{{ $shipment->etd }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="units" class="form-label">Units</label>
                                        <input type="text" class="form-control" id="units" value="{{ $shipment->units }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="pallets" class="form-label">Pallets</label>
                                        <input type="text" class="form-control" id="pallets" value="{{ $shipment->pallets }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="security_seals" class="form-label">Security Seal</label>
                                        <input type="text" class="form-control" id="security_seals" value="{{ $shipment->security_seals }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="notes" rows="3" readonly>{{ $shipment->notes }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="overhaul_id" class="form-label">Overhaul ID</label>
                                        <input type="text" class="form-control" id="overhaul_id" value="{{ $shipment->overhaul_id }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="device_number" class="form-label">Device Number</label>
                                        <input type="text" class="form-control" id="device_number" value="{{ $shipment->device_number }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="secondary_shipment_id" class="form-label">Secondary Shipment ID</label>
                                        <input type="text" class="form-control" id="secondary_shipment_id" value="{{ $shipment->secondary_shipment_id }}" readonly>
                                    </div>

                                    <!-- Agrega más campos si es necesario -->
                                </form>
                            </div>
                            <!-- Notes -->
                            <div class="tab-pane fade" id="notes{{ $shipment->stm_id }}" role="tabpanel" aria-labelledby="notes-tab{{ $shipment->stm_id }}">
                                <form id="shipmentForm" method="POST" action="/update-status-endpoint/{{ $shipment->pk_shipment }}">
                                    @method('PUT')

                                       @csrf
                                       <input type="hidden" name="_method" value="PUT">

                                       <select class="form-select" id="currentStatus" name="gnct_id_current_status">

                                           @foreach ($currentStatus as $status)
                                               <label for="currentStatus" class="form-label">Current Status</label>
                                               <option value="{{ $status->gnct_id }}" {{ old('gnct_id_current_status', $shipment->gnct_id_current_status) == $status->gnct_id ? 'selected' : '' }}>
                                                   {{ $status->gntc_description }}
                                               </option>
                                           @endforeach
                                       </select>

                                       <div class="mb-3">
                                           <label for="driverAssignmentDate" class="form-label">Driver Assignment Date</label>
                                           <input type="datetime-local" class="form-control" id="driverAssignmentDate" name="driver_assigned_date"
                                               value="{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('Y-m-d\TH:i') : '' }}">
                                       </div>
                                       <div class="mb-3">
                                           <label for="pickUpDate" class="form-label">Pick Up Date</label>
                                           <input type="datetime-local" class="form-control" id="pickUpDate" name="pick_up_date"
                                               value="{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('Y-m-d\TH:i') : '' }}">
                                       </div>
                                       <div class="mb-3">
                                           <label for="inTransitDate" class="form-label">In Transit Date</label>
                                           <input type="datetime-local" class="form-control" id="inTransitDate" name="intransit_date"
                                               value="{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('Y-m-d\TH:i') : '' }}">
                                       </div>

                                       <div class="mb-3">
                                           <label for="securedYardDate" class="form-label">Secured Yard Date</label>
                                           <input type="datetime-local" class="form-control" id="securedYardDate" name="secured_yarddate"
                                               value="{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('Y-m-d\TH:i') : '' }}">
                                       </div>
                                       <!-- Campos de Incidentes desactivados para pruebas -->
                                       <div class="mb-3">
                                           <label for="secIncident" class="form-label">Sec Incident</label>
                                           <select class="form-select" id="secIncident" name="sec_incident" disabled>
                                               <option value="null" selected>No Incident</option>
                                           </select>
                                       </div>
                                       <div class="mb-3">
                                           <label for="incidentType" class="form-label">Incident Type</label>
                                           <input type="text" class="form-control" id="incidentType" name="incident_type" disabled placeholder="Type of incident (if any)">
                                       </div>
                                       <div class="mb-3">
                                           <label for="incidentDate" class="form-label">Incident Date</label>
                                           <input type="datetime-local" class="form-control" id="incidentDate" name="incident_date" disabled>
                                       </div>
                                       <div class="d-flex justify-content-end">
                                           <button type="submit" class="btn btn-primary">Save</button>
                                       </div>
                                   </form>
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
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchByShipment');
            const table = document.getElementById('shipmentsTable');
            const rows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function () {
                const searchText = this.value.toLowerCase();

                rows.forEach(row => {
                    // Verificar si alguna celda contiene el texto buscado
                    const cells = row.querySelectorAll('td');
                    const match = Array.from(cells).some(cell =>
                        cell.textContent.toLowerCase().includes(searchText)
                    );

                    // Mostrar u ocultar la fila dependiendo de si coincide
                    row.style.display = match ? '' : 'none';
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Interceptar el envío del formulario
            $('#shipmentForm').on('submit', function (event) {
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

  /* Cambiar fondo y texto */

/* Estilo para las pestañas */
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

