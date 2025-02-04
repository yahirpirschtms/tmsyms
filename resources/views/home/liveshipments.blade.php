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

            <div class="col-auto mt-2" id="activeFilterDiv" style="display:none;">
                <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                    <span id="activeFilterText" style="color:white; font-size: small;" class="ms-2 me-2"></span>
                    <button id="closeActiveFilter" style="background-color:unset; color:white; font-size: small;" class="ms-2 me-2">X</button>
                </div>
            </div>
           <!-- Contenedor de tarjetas -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-2" id="cardsContainer">
                @foreach ($shipments as $shipment)
                    <div class="col shipment-card"
                        data-search="{{ $shipment->stm_id }} {{ $shipment->shipment_type }} {{ $shipment->status }} {{ $shipment->company->CoName ?? '' }} {{ $shipment->currentStatus->gntc_description ?? '' }} {{ $shipment->driver->drivername ?? '' }}">
                        <div class="card">
                            <div class="card-body text-black border border-1 rounded" style="">
                                <h5 class="card-title fw-bolder" style="color:#1e4877">{{ $shipment->stm_id }}</h5>
                                <p class="origin" style="color: #252525;">{{ $shipment->company->CoName ?? 'Origen no disponible' }}</p>

                                <p class="status" style="color: #252525;">{{ $shipment->currentStatus->gntc_description ?? 'Estado no disponible' }}</p>
                                <p class="" style="color: #252525;">{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y') : 'Approved ETA date not available' }}</p>
                                <p class="driver" style="color: #252525;">{{ $shipment->driver->drivername ?? 'Driver not assigned' }}</p>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm text-white" data-bs-toggle="offcanvas" style="background-color: rgb(13, 82, 200);"
                                        data-bs-target="#offcanvasUpdateStatus-{{ $shipment->stm_id }}">
                                        Update Status & Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        @foreach ($shipments as $shipment)
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateStatus-{{ $shipment->stm_id }}" aria-labelledby="offcanvasUpdateStatusLabel-{{ $shipment->stm_id }}">
                <div class="offcanvas-header" style="background-color:white; border:none; color:black">
                    <h5 id="offcanvasUpdateStatusLabel-{{ $shipment->stm_id }}">Shipment Status & Details</h5>
                    <button type="button" class="btn-close btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab-{{ $shipment->stm_id }}" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active"
                               id="pills-initial-status-tab-{{ $shipment->stm_id }}"
                               data-bs-toggle="pill"
                               href="#pills-initial-status-{{ $shipment->stm_id }}"
                               role="tab"
                               aria-controls="pills-initial-status-{{ $shipment->stm_id }}"
                               aria-selected="true">
                                Initial Shipment Status
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link"
                               id="pills-update-status-tab-{{ $shipment->stm_id }}"
                               data-bs-toggle="pill"
                               href="#pills-update-status-{{ $shipment->stm_id }}"
                               role="tab"
                               aria-controls="pills-update-status-{{ $shipment->stm_id }}"
                               aria-selected="false">
                                Update Shipment Status
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link"
                               id="pills-shipment-details-tab-{{ $shipment->stm_id }}"
                               data-bs-toggle="pill"
                               href="#pills-shipment-details-{{ $shipment->stm_id }}"
                               role="tab"
                               aria-controls="pills-shipment-details-{{ $shipment->stm_id }}"
                               aria-selected="false">
                                Shipment Details
                            </a>
                        </li>
                    </ul>

                    <form id="shipmentForm-{{ $shipment->stm_id }}" method="POST" action="/update-status-endpoint/{{ $shipment->pk_shipment }}">
                        <div class="tab-content" style="border: none;" id="pills-tabContent-{{ $shipment->stm_id }}">
                        <div class="tab-pane fade show active" id="pills-initial-status-{{ $shipment->stm_id }}" role="tabpanel" aria-labelledby="pills-initial-status-tab">

                                <div class="mb-3">
                                    <label for="stm_id" class="form-label">STM ID</label>
                                    <input type="text" class="form-control" id="stm_id" value="{{ $shipment->stm_id ?? 'STM ID no disponible' }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="device_number" class="form-label">Device Number</label>
                                    <input type="text" class="form-control" id="device_number" name="device_number" value="{{ $shipment->device_number }}">
                                </div>

                                <div class="mb-3">
                                    <label for="overhaul_id" class="form-label">Overhaul ID</label>
                                    <input type="text" class="form-control" id="overhaul_id" name="overhaul_id" value="{{ $shipment->overhaul_id }}">
                                </div>

                                <div class="mb-3">
                                    <label for="secondary_shipment_id" class="form-label">Secondary Shipment ID</label>
                                    <input type="text" class="form-control" id="secondary_shipment_id" name="secondary_shipment_id" value="{{ $shipment->secondary_shipment_id }}">
                                </div>

                                <div class="mb-3">
                                    <label for="reference" class="form-label">Landstar Reference</label>
                                    <input type="text" class="form-control" id="reference" name="reference" value="{{ $shipment->reference }}">
                                </div>

                                <div class="mb-3">
                                    <label for="shipment_type" class="form-label">Shipment Type</label>
                                    <input type="text" class="form-control" id="shipment_type" name="shipment_type" value="{{ $shipment->shipmentType->gntc_description ?? 'No disponible' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="etd-{{ $shipment->stm_id }}" class="form-label">ETD</label>
                                    <input type="text" class="form-control flatpickr" id="etd-{{ $shipment->stm_id }}" name="etd"
                                        value="{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--">
                                </div>

                                <!-- Origin -->
                                <div class="mb-3">
                                    <label for="origin-{{ $shipment->stm_id }}" class="form-label">Origin</label>
                                    <select class="form-select" id="origin-{{ $shipment->stm_id }}" name="origin">
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->pk_company }}"
                                                {{ old('origin', $shipment->origin) == $company->pk_company ? 'selected' : '' }}>
                                                {{ $company->CoName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Destination -->
                                <div class="mb-3">
                                    <label for="destination-{{ $shipment->stm_id }}" class="form-label">Destination</label>
                                    <select class="form-select" id="destination-{{ $shipment->stm_id }}" name="destination">
                                        @foreach ($facilities as $facility)
                                            <option value="{{ $facility->fac_id }}"
                                                {{ old('destination', $shipment->destination) == $facility->fac_id ? 'selected' : '' }}>
                                                {{ $facility->fac_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="preAlertedDatetime-{{ $shipment->stm_id }}" class="form-label">Pre-Alerted Datetime</label>
                                    <input type="text" class="form-control flatpickr" id="preAlertedDatetime-{{ $shipment->stm_id }}" name="pre_alerted_datetime"
                                        value="{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--">
                                </div>

                                <div class="mb-3">
                                    <label for="id_trailer-{{ $shipment->stm_id }}" class="form-label">Trailer ID</label>
                                    <input type="text" class="form-control" id="id_trailer-{{ $shipment->stm_id }}" name="id_trailer"
                                           value="{{ old('id_trailer', $shipment->id_trailer) }}" placeholder="Enter Trailer ID">
                                </div>


                               <!-- Trailer Owner -->
                                <div class="mb-3">
                                    <label for="trailer_owner-{{ $shipment->stm_id }}" class="form-label">Trailer Owner</label>
                                    <select class="form-select" id="trailer_owner-{{ $shipment->stm_id }}" name="trailer_owner">
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->pk_company }}"
                                                {{ old('trailer_owner', $shipment->origin) == $company->pk_company ? 'selected' : '' }}>
                                                {{ $company->CoName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Carrier -->
                                <div class="mb-3">
                                    <label for="carrier-{{ $shipment->stm_id }}" class="form-label">Carrier Dropping Trailer</label>
                                    <select class="form-select" id="carrier-{{ $shipment->stm_id }}" name="carrier">
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->pk_company }}"
                                                {{ old('carrier', $shipment->origin) == $company->pk_company ? 'selected' : '' }}>
                                                {{ $company->CoName }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="driver_truck-{{ $shipment->stm_id }}" class="form-label">Driver & Truck</label>
                                    <select class="form-select" id="driver_truck-{{ $shipment->stm_id }}" name="id_driver"
                                        onclick="checkAndChangeStatusForSelect('driver_truck-{{ $shipment->stm_id }}', 'Driver Assigned', '{{ $shipment->stm_id }}')">

                                        <!-- Si id_driver es nulo, dejamos el campo vacío sin ninguna opción seleccionada -->
                                        @if(is_null($shipment->id_driver))
                                            <option value="" disabled selected>Not available</option>
                                        @else
                                            <option value="">Select a driver</option>
                                        @endif

                                        @foreach ($drivers as $driver)
                                            @php
                                                // Concatenamos el drivername y truck para mostrar
                                                $currentDriverTruck = (!empty($shipment->driver->drivername) ? $shipment->driver->drivername : '') .
                                                                      (!empty($shipment->driver->drivername) && !empty($shipment->truck) ? ' - ' : '') .
                                                                      (!empty($shipment->truck) ? $shipment->truck : '');
                                            @endphp
                                            <!-- Usamos el id_driver como valor en el select -->
                                            <option value="{{ $driver->id_driver }}" {{ old('id_driver', $shipment->id_driver) == $driver->id_driver ? 'selected' : '' }}>
                                                {{ $driver->drivername }} - {{ $shipment->truck }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="units" class="form-label">Units</label>
                                    <input type="text" class="form-control" id="units" name="units" value="{{ $shipment->units }}">
                                </div>

                                <div class="mb-3">
                                    <label for="pallets" class="form-label">Pallets</label>
                                    <input type="text" class="form-control" id="pallets" name="pallets" value="{{ $shipment->pallets }}">
                                </div>

                                <div class="mb-3">
                                    <label for="security_seals" class="form-label">Security Seal</label>
                                    <input type="text" class="form-control" id="security_seals" name="security_seals" value="{{ $shipment->security_seals }}">
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ $shipment->notes }}</textarea>
                                </div>


                                <!-- Agrega más campos si es necesario -->
                                <button id="nextButton-{{ $shipment->stm_id }}" class="btn btn-primary" type="button">Next</button>

                        </div>
                        <div class="tab-pane fade"
                            id="pills-update-status-{{ $shipment->stm_id }}"
                            role="tabpanel"
                            aria-labelledby="pills-update-status-tab-{{ $shipment->stm_id }}">

                                @method('PUT')
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <label for="currentStatus" class="form-label">Current Status</label>
                                <select class="form-select" id="currentStatus-{{ $shipment->stm_id }}" name="gnct_id_current_status">
                                    @foreach ($currentStatus as $status)
                                        <option value="{{ $status->gnct_id }}"
                                            {{ old('gnct_id_current_status', $shipment->gnct_id_current_status) == $status->gnct_id ? 'selected' : '' }}>
                                            {{ $status->gntc_description }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="mb-3">
                                    <label for="driverAssignmentDate-{{ $shipment->stm_id }}" class="form-label">Driver Assignment Date</label>
                                    <input type="text" class="form-control flatpickr" id="driverAssignmentDate-{{ $shipment->stm_id }}" name="driver_assigned_date"
                                        value="{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i') : '' }}"
                                         placeholder="mm/dd/yyyy --:--"
                                        onfocus="checkAndChangeStatus('driverAssignmentDate-{{ $shipment->stm_id }}', 'Driver Assigned', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="pickUpDate-{{ $shipment->stm_id }}" class="form-label">Pick Up Date</label>
                                    <input type="text" class="form-control flatpickr" id="pickUpDate-{{ $shipment->stm_id }}" name="pick_up_date"
                                        value="{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i') : '' }}"
                                         placeholder="mm/dd/yyyy --:--"
                                        onfocus="checkAndChangeStatus('pickUpDate-{{ $shipment->stm_id }}', 'Picked Up', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="inTransitDate-{{ $shipment->stm_id }}" class="form-label">In Transit Date</label>
                                    <input type="text" class="form-control flatpickr" id="inTransitDate-{{ $shipment->stm_id }}" name="intransit_date"
                                        value="{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        onfocus="checkAndChangeStatus('inTransitDate-{{ $shipment->stm_id }}', 'In Transit', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="securedYardDate-{{ $shipment->stm_id }}" class="form-label">Secured Yard Date</label>
                                    <input type="text" class="form-control flatpickr" id="securedYardDate-{{ $shipment->stm_id }}" name="secured_yarddate"
                                        value="{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        onfocus="checkAndChangeStatus('securedYardDate-{{ $shipment->stm_id }}', 'Secured Yard', '{{ $shipment->stm_id }}')">
                                </div>

                                <!-- Campos de Incidentes desactivados para pruebas -->
                                <div class="mb-3">
                                    <label for="secIncident-{{ $shipment->stm_id }}" class="form-label">Sec Incident</label>
                                    <select class="form-select" id="secIncident-{{ $shipment->stm_id }}" name="sec_incident" disabled>
                                        <option value="null" selected>No Incident</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="incidentType-{{ $shipment->stm_id }}" class="form-label">Incident Type</label>
                                    <input type="text" class="form-control" id="incidentType-{{ $shipment->stm_id }}" name="incident_type" disabled placeholder="Type of incident (if any)">
                                </div>
                                <div class="mb-3">
                                    <label for="incidentDate-{{ $shipment->stm_id }}" class="form-label">Incident Date</label>
                                    <input type="datetime-local" class="form-control" id="incidentDate-{{ $shipment->stm_id }}" name="incident_date" disabled>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="offcanvas">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                    </form>

                        </div>
                        <div class="tab-pane fade"
                            id="pills-shipment-details-{{ $shipment->stm_id }}"
                            role="tabpanel"
                            aria-labelledby="pills-shipment-details-tab-{{ $shipment->stm_id }}">
                            <form id="shipmentDetailsForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">STM ID</label>
                                    <p>{{ $shipment->stm_id ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reference</label>
                                    <p>{{ $shipment->reference ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bonded</label>
                                    <p>{{ $shipment->bonded ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Origin</label>
                                    <p>{{ $shipment->company->CoName ?? 'Origen no disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Destination</label>
                                    <p>{{ $shipment->destinationFacility->fac_name ?? 'Destino no disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pre-Alerted Date & Time</label>
                                    <p>{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trailer ID</label>
                                    <p>{{ $shipment->id_trailer ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    <p>{{ $shipment->company->CoName ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trailer</label>
                                    <p>{{ $shipment->trailer ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Truck</label>
                                    <p>{{ $shipment->truck ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Driver ID</label>
                                    <p>{{ $shipment->id_driver ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ETD (Estimated Time of Departure)</label>
                                    <p>{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Units</label>
                                    <p>{{ $shipment->units ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pallets</label>
                                    <p>{{ $shipment->pallets ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Security Seals</label>
                                    <p>{{ $shipment->security_seals ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Overhaul ID</label>
                                    <p>{{ $shipment->overhaul_id ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Device Number</label>
                                    <p>{{ $shipment->device_number ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secondary Shipment ID</label>
                                    <p>{{ $shipment->secondary_shipment_id ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Driver Assigned Date</label>
                                    <p>{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pick-Up Date</label>
                                    <p>{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">In Transit Date</label>
                                    <p>{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secured Yard Date</label>
                                    <p>{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Current Status</label>
                                    <p>{{ $shipment->currentStatus->gntc_description ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Shipment Type </label>
                                    <p>{{ $shipment->shipmentType->gntc_description ?? 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Delivered Date</label>
                                    <p>{{ $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">At Door Date</label>
                                    <p>{{ $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Approved ETA date & Time</label>
                                    <p>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i:s') : 'No disponible' }}</p>
                                </div>
                            </form>

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
    $(document).on('submit', '[id^="shipmentForm"]', function (event) {
        event.preventDefault(); // Previene el envío estándar del formulario
        console.log('Formulario enviado (delegado)');

        let formAction = $(this).attr('action');
        let formData = $(this).serialize();

        $.ajax({
            url: formAction,
            method: 'PUT',
            data: formData,
            beforeSend: function () {
                Swal.fire({
                    title: 'Enviando datos...',
                    text: 'Por favor espera mientras se procesan los datos.',
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
                    text: response.message || 'Los datos se actualizaron correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload(); // Recargar la página para reflejar los cambios
                });
                console.log('Respuesta recibida:', response);
            },
            error: function (xhr) {
                let errorMessage = xhr.responseJSON?.message || 'Ocurrió un error al actualizar el estado.';
                Swal.fire({
                    title: 'Error',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                console.error('Error en la solicitud:', xhr.responseJSON || xhr.responseText);
            },
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


<script>
    function checkAndChangeStatus(dateFieldId, statusDescription, shipmentId) {
        const dateField = document.getElementById(dateFieldId);
        const currentDateValue = dateField.value;

        // Verificar si ya existe una fecha en el campo y evitar el cambio de estado
        if (currentDateValue) {
            console.log(`El campo ${dateFieldId} ya tiene una fecha, no se cambiará el estado.`);
            return; // Si ya hay una fecha, no cambiar el estado
        }

        // Cambiar el estado solo si el campo está vacío, usando la descripción
        changeStatusByDescription(statusDescription, shipmentId);
    }

    function checkAndChangeStatusForSelect(selectFieldId, statusDescription, shipmentId) {
    const selectField = document.getElementById(selectFieldId);

    // Cambiar el estado inmediatamente al hacer clic
    changeStatusByDescription(statusDescription, shipmentId);
    }

    // Cambiar el estado utilizando la descripción
    function changeStatusByDescription(statusDescription, shipmentId) {
        console.log('shipmentId recibido:', shipmentId); // Verifica el valor de shipmentId
        console.log('Estado recibido:', statusDescription); // Verifica la descripción del estado

        // Aquí es donde mapeamos la descripción al valor correspondiente de gntc_description
        const statusMapping = {
            'Picked Up': 'Picked Up',       // gntc_description 'Picked Up'
            'Driver Assigned': 'Driver Assigned', // gntc_description 'Driver Assigned'
            'In Transit': 'In Transit',     // gntc_description 'In Transit'
            'Secured Yard': 'Secured Yard',
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
    flatpickr('.flatpickr', {
        dateFormat: 'm/d/Y H:i', // Define el formato que deseas mostrar
        enableTime: true, // Habilita la selección de hora
        time_24hr: true, // Si deseas que la hora sea en formato de 24 horas
        onOpen: function (selectedDates, dateStr, instance) {
            // Si el campo está vacío, se coloca la fecha y hora actual
            if (dateStr === "") {
                instance.setDate(new Date(), true); // Establece la fecha actual
            }
        },
    });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[id^='nextButton-']").forEach(button => {
        button.addEventListener("click", function () {
            // Obtener el ID del shipment
            var shipmentId = button.id.replace("nextButton-", "");

            // Buscar la pestaña de destino
            var nextTab = document.querySelector("#pills-update-status-tab-" + shipmentId);

            if (nextTab) {
                console.log("Cambiando a la pestaña:", nextTab.id); // Debug
                var tab = new bootstrap.Tab(nextTab);
                tab.show();

                // Esperar a que la pestaña se muestre antes de hacer scroll
                setTimeout(() => {
                    document.querySelector("#pills-update-status-" + shipmentId).scrollIntoView({ behavior: "smooth" });
                }, 100);
            } else {
                console.error("No se encontró la pestaña de destino.");
            }
        });
    });
});
</script>

<script>
 // JavaScript para cambiar de pestaña al hacer clic en "Next"
 document.getElementById('nextButton').addEventListener('click', function () {
    // Obtener el STM ID dinámicamente
    var stmId = '{{ $shipment->stm_id }}';

    // Obtener la siguiente pestaña basada en el STM ID
    var nextTab = document.getElementById('pills-update-status-tab-' + stmId);

    if (nextTab) {
        // Cambiar a la siguiente pestaña usando Bootstrap
        var tab = new bootstrap.Tab(nextTab);
        tab.show(); // Muestra la siguiente pestaña
    }
});
</script>
@endsection

@section('custom-css')
<style>
/* Estilo general del offcanvas */
.offcanvas-header {
    background-color: #343a40; /* Fondo oscuro */
    color: #fff; /* Texto blanco */
    border-bottom: 2px solid #495057; /* Borde gris */
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

/* Estilo de las pestañas en el offcanvas */
/* Estilo de las pestañas en el offcanvas */
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

/* Estilo para el contenido del offcanvas */
.offcanvas-body {
    padding: 20px;
    background-color: #f8f9fa; /* Fondo claro */
}

/* Estilo para el footer del offcanvas */
.offcanvas-footer {
    padding: 10px;
    background-color: #e9ecef; /* Fondo gris claro */
    border-top: 1px solid #dee2e6;
}
</style>
