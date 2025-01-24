@extends('layouts.app-master')

@section('title', 'Live Shipments')

@section('content')
    @auth
        <div class="container  my-4">
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" >Live Shipments</h2>
            </div>
            <div class="d-flex justify-content-end mt-4 mb-2">
                <!-- Search Input for All Shipments -->
                <div style="position: relative; display: inline-block; width: 100%;" class="me-4">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #6c757d; cursor: pointer;" onclick="document.getElementById('searchByShipment').focus()"></i>
                    <input class="form-control" type="search" placeholder="Search Live Shipments" name="searchByShipment" id="searchByShipment" aria-label="Search" style="padding-left: 30px;">
                </div>

                <!-- Refresh Table Button -->
                <button type="button" class="btn me-2 btn-primary" id="refreshshipmentstable" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh Table">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </button>

                <!-- Add More Filters Button -->
                <button class="btn" id="addmorefiltersallshipments" style="color: white;background-color:orange;" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasaddmorefilters" aria-controls="offcanvasaddmorefilters">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>

             <!--OffCanvas añadir más filtros-->
             <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasaddmorefilters" aria-labelledby="offcanvasaddmorefiltersLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasaddmorefiltersLabel">Add More Filters</h5>
                    <button type="button" id="offcanvasaddmorefiltersclosebutton" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">

                    <!-- Filtro por Origin -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplystmfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsstmfilter" aria-expanded="false" aria-controls="multiCollapsstmfilter">Origin</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsstmfilter">
                                    <input type="text" class="form-control" id="inputapplyoriginfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyoriginfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Status-->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplysecondaryshipmentidfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapssecondaryshipmentidfilter" aria-expanded="false" aria-controls="multiCollapssecondaryshipmentidfilter">Status</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapssecondaryshipmentidfilter">
                                    <input type="text" class="form-control" id="inputapplystatusidfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applystatusidfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Driver -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplylandstarreferencefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapslandstarreferencefilter" aria-expanded="false" aria-controls="multiCollapslandstarreferencefilter">Driver</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapslandstarreferencefilter">
                                    <input type="text" class="form-control" id="inputapplydriverfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydriverfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


           <!-- Contenedor de tarjetas -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="cardsContainer">
                @foreach ($shipments as $shipment)
                    <div class="col shipment-card"
                        data-search="{{ $shipment->stm_id }} {{ $shipment->shipment_type }} {{ $shipment->status }} {{ $shipment->company->CoName ?? '' }} {{ $shipment->currentStatus->gntc_description ?? '' }} {{ $shipment->driver->drivername ?? '' }}">
                        <div class="card">
                            <div class="card-body text-white bg-dark">
                                <h5 class="card-title">{{ $shipment->stm_id }}</h5>
                                <p class="origin">{{ $shipment->company->CoName ?? 'Origen no disponible' }}</p>

                                <p class="status">{{ $shipment->currentStatus->gntc_description ?? 'Estado no disponible' }}</p>
                                <p>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y') : 'Approved ETA date no disponible' }}</p>
                                <p class="driver">{{ $shipment->driver->drivername ?? 'Conductor no asignado' }}</p>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasUpdateStatus"
                                        onclick="populateOffcanvas({{ json_encode($shipment) }})">Update Status & Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-auto mt-2" id="activeFilterDiv" style="display:none;">
            <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                <span id="activeFilterText" style="color:white; font-size: small;" class="ms-2 me-2"></span>
                <button id="closeActiveFilter" style="background-color:unset; color:white; font-size: small;" class="ms-2 me-2">X</button>
            </div>
        </div>
        <!-- Offcanvas para Update Status -->
        @foreach ($shipments as $shipment)
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateStatus" aria-labelledby="offcanvasUpdateStatusLabel">
                <div class="offcanvas-header">
                <h5 id="offcanvasUpdateStatusLabel">Shipment Status & Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                 </div>
             <div class="offcanvas-body">
                    <!-- Pestañas -->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-initial-status-tab" data-bs-toggle="pill" href="#pills-initial-status" role="tab" aria-controls="pills-initial-status" aria-selected="true">Initial Shipment Status</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-update-status-tab" data-bs-toggle="pill" href="#pills-update-status" role="tab" aria-controls="pills-update-status" aria-selected="false">Update Shipment Status</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-shipment-details-tab" data-bs-toggle="pill" href="#pills-shipment-details" role="tab" aria-controls="pills-shipment-details" aria-selected="false">Shipment Details</a>
                        </li>
                    </ul>
                    <!-- Initial Shipment info -->
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-initial-status" role="tabpanel" aria-labelledby="pills-initial-status-tab">
                        <form>
                            <div class="mb-3">
                                <label for="stm_id" class="form-label">STM ID</label>
                                <input type="text" class="form-control" id="stm_id" value="{{ $shipment->stm_id ?? 'STM ID no disponible' }}" readonly>
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
                                <input type="text" class="form-control" id="origin" value="{{ $shipment->company->CoName ?? 'Origen no disponible' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="destination" class="form-label">Destination</label>
                                <input type="text" class="form-control" id="destination"
                                    value="{{ $shipment->destinationFacility->fac_name ?? 'Destination not available' }}" readonly>
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
                                <input type="text" class="form-control" id="id_company" value="{{ $shipment->company->id_company }}" readonly>
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



                    <!-- Update Shipment Status -->
                    <div class="tab-pane fade" id="pills-update-status" role="tabpanel" aria-labelledby="pills-update-status-tab">
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


                    <!-- Shipment Details Tab Content -->
                    <div class="tab-pane fade show active"  id="pills-shipment-details" role="tabpanel" aria-labelledby="pills-shipment-details-tab">
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
                            <p>{{ $shipment->company->CoName ?? 'Origen no disponible' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Destination</label>
                            <p>{{ $shipment->destinationFacility->fac_name }}</p>
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
                            <p>{{ $shipment->company->id_company }}</p>
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

                        <div class="mb-3">
                            <label class="form-label">Approved ETA date & Time</label>
                            <p>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i:s') : 'Approved ETA date no disponible' }}</p>
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
                    // Mostrar un indicador de carga con SweetAlert2
                    Swal.fire({
                        title: 'Enviando datos...',
                        text: 'Por favor espera mientras procesamos tu solicitud.',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading(); // Mostrar cargando
                        }
                    });
                },
                success: function (response) {
                    // Manejar la respuesta exitosa
                    Swal.fire({
                        title: '¡Éxito!',
                        text: response.message || 'El formulario fue actualizado correctamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload(); // Recargar la página para ver los cambios
                    });

                    console.log(response);
                },
                error: function (xhr) {
                    // Manejar errores
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
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchByShipment');
            const cards = document.querySelectorAll('.shipment-card');

            searchInput.addEventListener('keyup', function () {
                const query = this.value.toLowerCase();

                cards.forEach(card => {
                    const cardText = card.innerText.toLowerCase();
                    if (cardText.includes(query)) {
                        card.style.display = ''; // Mostrar tarjeta
                    } else {
                        card.style.display = 'none'; // Ocultar tarjeta
                    }
                });
            });
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cardsContainer = document.getElementById("cardsContainer");  // Contenedor de las tarjetas
        const cardElements = cardsContainer.querySelectorAll(".card");  // Todas las tarjetas dentro del contenedor

        // Función común para aplicar filtros
        function applyFilter(inputId, buttonId, filterClass) {
            const inputFilter = document.getElementById(inputId);  // Campo de filtro
            const applyButton = document.getElementById(buttonId);  // Botón "Apply"
            const $activeFilterDiv = $('#activeFilterDiv');
            const $activeFilterText = $('#activeFilterText');
            const $closeActiveFilterButton = $('#closeActiveFilter');

            // Lógica para aplicar el filtro
            if (applyButton) {
                applyButton.addEventListener("click", function () {
                    const filterValue = inputFilter.value.trim().toLowerCase();  // Obtener el valor y convertirlo a minúsculas

                    if (filterValue) {
                        console.log("Filtro aplicado: " + filterValue);

                        // Mostrar el filtro aplicado con el texto "Filtro: "
                        $activeFilterText.text("Filtro" + inputFilter.placeholder + ": " + filterValue);
                        $activeFilterDiv.show();

                        // Filtrar las tarjetas
                        cardElements.forEach(card => {
                            const cardText = card.querySelector(filterClass) ? card.querySelector(filterClass).textContent : "";  // Obtener el texto de la clase de filtro

                            if (cardText.toLowerCase().includes(filterValue)) {
                                card.style.display = "";  // Mostrar la tarjeta si coincide con el filtro
                            } else {
                                card.style.display = "none";  // Ocultar la tarjeta si no coincide
                            }
                        });
                    } else {
                        // Si no hay valor en el filtro, mostrar todas las tarjetas
                        cardElements.forEach(card => card.style.display = "");
                        $activeFilterDiv.hide();  // Ocultar la sección de filtro aplicado si no hay filtro
                    }
                });
            }

            // Lógica para cerrar el filtro y resetear las tarjetas al hacer clic en la "X"
            if ($closeActiveFilterButton) {
                $closeActiveFilterButton.on('click', function () {
                    // Limpiar el campo de filtro y mostrar todas las tarjetas
                    inputFilter.value = "";
                    cardElements.forEach(card => card.style.display = "");

                    // Ocultar la sección de filtro aplicado
                    $activeFilterDiv.hide();
                });
            }
        }

        // Filtro por Origin (Filtro basado en la clase .origin dentro de cada tarjeta)
        applyFilter('inputapplyoriginfilter', 'applyoriginfilter', '.origin');  // Filtro por Origin

        // Filtro por Status (Filtro basado en la clase .status dentro de cada tarjeta)
        applyFilter('inputapplystatusidfilter', 'applystatusidfilter', '.status');  // Filtro por Status

        // Filtro por Driver (Filtro basado en la clase .driver dentro de cada tarjeta)
        applyFilter('inputapplydriverfilter', 'applydriverfilter', '.driver');  // Filtro por Driver

        // Evento para el botón de refresh
        const refreshButton = document.getElementById("refreshshipmentstable");
        if (refreshButton) {
            refreshButton.addEventListener("click", function () {
                // Recargar las tarjetas, por ejemplo, mostrando todas las tarjetas y limpiando los filtros
                cardElements.forEach(card => card.style.display = "");  // Mostrar todas las tarjetas
                const inputs = document.querySelectorAll('input');  // Obtener todos los campos de filtro
                inputs.forEach(input => input.value = "");  // Limpiar los filtros
                $('#activeFilterDiv').hide();  // Ocultar la sección del filtro activo
                console.log("Tarjetas recargadas");
            });
        }
    });
</script>
@endsection

@section('custom-css')
<style>
    /* Estilo general del offcanvas */
    .offcanvas-header {
        background-color: #343a40;
        color: #fff;
        border-bottom: 2px solid #495057;
    }

    .offcanvas-title {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .btn-close {
        color: #fff;
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Estilo de las pestañas */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        justify-content: center;
        padding: 10px 0;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .nav-tabs .nav-link {
        font-size: 1rem;
        font-weight: bold;
        color: #495057; /* Color visible para pestañas inactivas */
        background-color: #e9ecef; /* Fondo gris claro para pestañas inactivas */
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin: 0 5px;
        padding: 10px 15px;
        transition: all 0.3s ease-in-out;
    }

    .nav-tabs .nav-link.active {
        color: #fff; /* Texto blanco para pestaña activa */
        background-color: #0d6efd; /* Fondo azul Bootstrap para pestaña activa */
        border-color: #0d6efd; /* Bordes azules */
    }

    .nav-tabs .nav-link:hover {
        background-color: #d6d8db; /* Fondo más claro al pasar el mouse */
        color: #212529; /* Texto más oscuro */
    }

    /* Contenido de las pestañas */
    .tab-content {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-top: 10px;
    }
</style>
