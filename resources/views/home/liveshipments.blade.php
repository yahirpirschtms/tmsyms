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

                    <!-- Filtro por Status -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplystatusidfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsstatusidfilter" aria-expanded="false" aria-controls="multiCollapsstatusidfilter">
                            Status
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsstatusidfilter">
                                    <div class="form-check">
                                        <input class="form-check-input status-filter" type="checkbox" value="New" id="statusNew">
                                        <label class="form-check-label" for="statusNew">New</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status-filter" type="checkbox" value="Prealerted" id="statusPrealerted">
                                        <label class="form-check-label" for="statusPrealerted">Prealerted</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status-filter" type="checkbox" value="Driver Assigned" id="statusDriverAssigned">
                                        <label class="form-check-label" for="statusDriverAssigned">Driver Assigned</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status-filter" type="checkbox" value="Picked Up" id="statusPickedUp">
                                        <label class="form-check-label" for="statusPickedUp">Picked Up</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status-filter" type="checkbox" value="Finalized" id="statusFinalized">
                                        <label class="form-check-label" for="statusFinalized">Finalized</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status-filter" type="checkbox" value="Delivered" id="statusDelivered">
                                        <label class="form-check-label" for="statusDelivered">Delivered</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status-filter" type="checkbox" value="Secured Yard" id="statusSecuredYard">
                                        <label class="form-check-label" for="statusSecuredYard">Secured Yard</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status-filter" type="checkbox" value="Dock Door" id="statusDockDoor">
                                        <label class="form-check-label" for="statusDockDoor">Dock Door</label>
                                    </div>
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
                        data-search="{{ $shipment->stm_id }} {{ $shipment->shipment_type }} {{ $shipment->status }} {{ $shipment->origin->CoName ?? '' }} {{ $shipment->currentStatus->gntc_description ?? '' }} {{ $shipment->driver->drivername ?? '' }}">
                        <div class="card">
                            <div class="card-body text-black border border-1 rounded" style="">
                                <h5 class="card-title fw-bolder" style="color:#1e4877">{{ $shipment->stm_id }}</h5>
                                <p class="origin" style="color: #252525;">{{ $shipment->company->CoName ?? 'Origin not Availiable' }}</p>

                                <p class="status" style="color: #252525;">{{ $shipment->currentStatus->gntc_description ?? 'Status Not Availiable' }}</p>
                                <p class="" style="color: #252525;">{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y') : '' }}</p>
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

                    <form id="shipmentForm-{{ $shipment->stm_id }}" method="POST" action="/update-status-endpoint/{{ $shipment->pk_shipment }}" onsubmit="return validateShipment('{{ $shipment->stm_id }}')">
                        <div class="tab-content" style="border: none;" id="pills-tabContent-{{ $shipment->stm_id }}">
                        <div class="tab-pane fade show active" id="pills-initial-status-{{ $shipment->stm_id }}" role="tabpanel" aria-labelledby="pills-initial-status-tab">

                            <input type="hidden" name="pk_shipment" value="{{ $shipment->pk_shipment }}">

                            <div class="mb-3">
                                <label for="stm_id" class="form-label">STM ID</label>
                                <input type="text" class="form-control" id="stm_id" value="{{ $shipment->stm_id ?? 'STM ID Not Available' }}" readonly data-original="{{ $shipment->stm_id ?? 'STM ID not available' }}">
                            </div>

                            <div class="mb-3">
                                <label for="tracker1" class="form-label">Tracker 1</label>
                                <input type="text" class="form-control" id="tracker1" name="tracker1" value="{{ $shipment->tracker1 }}" data-original="{{ $shipment->tracker1 }}">
                            </div>

                            <div class="mb-3">
                                <label for="tracker2" class="form-label">Tracker 2</label>
                                <input type="text" class="form-control" id="tracker2" name="tracker2" value="{{ $shipment->tracker2 }}" data-original="{{ $shipment->tracker2 }}">
                            </div>

                            <div class="mb-3">
                                <label for="tracker3" class="form-label">Tracker 3</label>
                                <input type="text" class="form-control" id="tracker3" name="tracker3" value="{{ $shipment->tracker3 }}" data-original="{{ $shipment->tracker3 }}">
                            </div>


                            <div class="mb-3">
                                <label for="security_company_id" class="form-label">Security Company ID</label>
                                <input type="text" class="form-control" id="security_company_id" name="security_company_id" value="{{ $shipment->security_company_id }}" data-original="{{ $shipment->security_company_id }}">
                            </div>

                            <div class="mb-3">
                                <label for="securityCompany" class="form-label">Security Company</label>
                                <input type="text" class="form-control" id="securityCompany-{{ $shipment->stm_id }}"
                                       value="{{ $shipment->securityCompany->gntc_description ?? '-- No Company Selected --' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="secondary_shipment_id" class="form-label">Secondary Shipment ID</label>
                                <input type="text" class="form-control" id="secondary_shipment_id" name="secondary_shipment_id" value="{{ $shipment->secondary_shipment_id }}" data-original="{{ $shipment->secondary_shipment_id }}">
                            </div>

                            <div class="mb-3">
                                <label for="reference" class="form-label">Carrier Reference</label>
                                <input type="text" class="form-control" id="reference" name="reference" value="{{ $shipment->reference }}" data-original="{{ $shipment->reference }}">
                            </div>

                            <div class="mb-3">
                                <label for="shipment_type" class="form-label">Shipment Type</label>
                                <input type="text" class="form-control" id="shipment_type" name="shipment_type" value="{{ $shipment->shipmentType->gntc_description ?? 'Not Available' }}" data-original="{{ $shipment->shipmentType->gntc_description ?? 'No disponible' }}">
                            </div>

                            <div class="mb-3">
                                <label for="etd-{{ $shipment->stm_id }}" class="form-label">ETD</label>
                                <input type="text" class="form-control flatpickr" id="etd-{{ $shipment->stm_id }}" name="etd"
                                    value="{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i') : '' }}"
                                    placeholder="mm/dd/yyyy --:--" data-original="{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i') : '' }}">
                            </div>

                           <!-- Origin -->
                           <div class="mb-3">
                            <label for="origin-{{ $shipment->stm_id }}" class="form-label">Origin</label>
                            <!-- Campo oculto para enviar el pk_company de origin -->
                            <input type="hidden" id="origin-{{ $shipment->stm_id }}" name="origin"
                                value="{{ $shipment->company->pk_company ?? '' }}">

                            <!-- Campo de solo lectura para mostrar el CoName de origin -->
                            <input type="text" class="form-control"
                                value="{{ $shipment->company->CoName ?? 'N/A' }}" readonly>
                            </div>

                            <!-- Destination -->
                            <div class="mb-3">
                                <label for="destination-{{ $shipment->stm_id }}" class="form-label">Destination</label>
                                <!-- Campo oculto para enviar el pk_company de destination -->
                                <input type="hidden" id="destination-{{ $shipment->stm_id }}" name="destination"
                                    value="{{ $shipment->companydest->pk_company ?? '' }}">

                                <!-- Campo de solo lectura para mostrar el CoName de destination -->
                                <input type="text" class="form-control"
                                    value="{{ $shipment->companydest->CoName ?? 'N/A' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="preAlertedDatetime-{{ $shipment->stm_id }}" class="form-label">Pre-Alerted Datetime</label>
                                <input type="text" class="form-control flatpickr" id="preAlertedDatetime-{{ $shipment->stm_id }}" name="pre_alerted_datetime"
                                    value="{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') : '' }}"
                                    placeholder="mm/dd/yyyy --:--" data-original="{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') : '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="id_trailer-{{ $shipment->stm_id }}" class="form-label">Trailer ID</label>
                                <input type="text" class="form-control" id="id_trailer-{{ $shipment->stm_id }}" name="id_trailer"
                                       value="{{ old('id_trailer', $shipment->id_trailer) }}" placeholder="Enter Trailer ID" data-original="{{ old('id_trailer', $shipment->id_trailer) }}">
                            </div>

                            <!-- Trailer Owner -->
                            <div class="mb-3">
                                <label for="trailer_owner-{{ $shipment->stm_id }}" class="form-label">Trailer Owner</label>
                                <select class="form-select" id="trailer_owner-{{ $shipment->stm_id }}" name="trailer_owner" data-original="{{ $shipment->trailer_owner }}">
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
                                <select class="form-select" id="carrier-{{ $shipment->stm_id }}" name="carrier" data-original="{{ $shipment->carrier }}">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->pk_company }}"
                                            {{ old('carrier', $shipment->origin) == $company->pk_company ? 'selected' : '' }}>
                                            {{ $company->CoName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="driver-{{ $shipment->stm_id }}" class="form-label">Driver</label>
                                <select class="form-select" id="driver-{{ $shipment->stm_id }}" name="id_driver" data-original="{{ $shipment->id_driver }}">
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->pk_driver }}"
                                            {{ old('id_driver', $shipment->id_driver) == $driver->pk_driver ? 'selected' : '' }}>
                                            {{ $driver->drivername }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="Truck" class="form-label">Truck</label>
                                <input type="text" class="form-control" id="Truck" name="truck" value="{{ $shipment->truck }}" data-original="{{ $shipment->truck }}">
                            </div>

                            <div class="mb-3">
                                <label for="units-{{ $shipment->stm_id }}" class="form-label">Units</label>
                                <input type="text" class="form-control" id="units-{{ $shipment->stm_id }}" name="units" value="{{ $shipment->units }}" oninput="validateShipment('{{ $shipment->stm_id }}')" data-original="{{ $shipment->units }}">
                            </div>

                            <div class="mb-3">
                                <label for="pallets-{{ $shipment->stm_id }}" class="form-label">Pallets</label>
                                <input type="text" class="form-control" id="pallets-{{ $shipment->stm_id }}" name="pallets" value="{{ $shipment->pallets }}" oninput="validateShipment('{{ $shipment->stm_id }}')" data-original="{{ $shipment->pallets }}">
                            </div>

                            <span id="error-message-{{ $shipment->stm_id }}" style="color: red; display: none;"></span>

                            <div class="mb-3">
                                <label for="seal1" class="form-label">Seal 1</label>
                                <input type="text" class="form-control" id="seal1" name="seal1" value="{{ $shipment->seal1 }}" data-original="{{ $shipment->seal1 }}">
                            </div>

                            <div class="mb-3">
                                <label for="seal2" class="form-label">Seal 2</label>
                                <input type="text" class="form-control" id="seal2" name="seal2" value="{{ $shipment->seal2 }}" data-original="{{ $shipment->seal2 }}">
                            </div>

                            <div class="mb-3" hidden>
                                <label for="lane" class="form-label">Lane</label>
                                <input type="text" class="form-control" id="lane" name="lane" value="{{ $shipment->lane }}" data-original="{{ $shipment->lane }}">
                            </div>

                            <div class="mb-3">
                                <label for="late_reason" class="form-label">Late Reason</label>
                                <select class="form-select" id="late_reason-{{ $shipment->stm_id }}" name="late_reason" data-original="{{ old('late_reason', $shipment->late_reason_id) }}">
                                    <option value="">-- Select Reason --</option>
                                    @foreach ($lateReasons as $reason)
                                        <option value="{{ $reason->pk_id }}"
                                            {{ old('late_reason', $shipment->late_reason) == $reason->pk_id ? 'selected' : '' }}>
                                            {{ $reason->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" data-original="{{ $shipment->notes }}">{{ $shipment->notes }}</textarea>
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
                                <select class="form-select" id="currentStatus-{{ $shipment->stm_id }}" name="gnct_id_current_status" data-original="{{ old('gnct_id_current_status', $shipment->gnct_id_current_status) }}">
                                    @foreach ($currentStatus as $status)
                                        <option value="{{ $status->gnct_id }}"
                                            {{ old('gnct_id_current_status', $shipment->gnct_id_current_status) == $status->gnct_id ? 'selected' : '' }}>
                                            {{ $status->gntc_description }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="mb-3">
                                    <label for="dockDoorDate-{{ $shipment->stm_id }}" class="form-label">Dock Door Date</label>
                                    <input type="text" class="form-control flatpickr" id="dockDoorDate-{{ $shipment->stm_id }}" name="dock_door_date"
                                        value="{{ $shipment->dock_door_date ? \Carbon\Carbon::parse($shipment->dock_door_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->dock_door_date ? \Carbon\Carbon::parse($shipment->dock_door_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('dockDoorDate-{{ $shipment->stm_id }}', 'Dock Door', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="driverAssignmentDate-{{ $shipment->stm_id }}" class="form-label">Driver Assignment Date</label>
                                    <input type="text" class="form-control flatpickr" id="driverAssignmentDate-{{ $shipment->stm_id }}" name="driver_assigned_date"
                                        value="{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('driverAssignmentDate-{{ $shipment->stm_id }}', 'Driver Assigned', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="pickUpDate-{{ $shipment->stm_id }}" class="form-label">Pick Up Date</label>
                                    <input type="text" class="form-control flatpickr" id="pickUpDate-{{ $shipment->stm_id }}" name="pick_up_date"
                                        value="{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('pickUpDate-{{ $shipment->stm_id }}', 'Picked Up', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="inTransitDate-{{ $shipment->stm_id }}" class="form-label">In Transit Date</label>
                                    <input type="text" class="form-control flatpickr" id="inTransitDate-{{ $shipment->stm_id }}" name="intransit_date"
                                        value="{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('inTransitDate-{{ $shipment->stm_id }}', 'In Transit', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="securedYardDate-{{ $shipment->stm_id }}" class="form-label">Secured Yard Date</label>
                                    <input type="text" class="form-control flatpickr" id="securedYardDate-{{ $shipment->stm_id }}" name="secured_yarddate"
                                        value="{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('securedYardDate-{{ $shipment->stm_id }}', 'Secured Yard', '{{ $shipment->stm_id }}')">
                                </div>

                                <!-- Campos de Incidentes desactivados para pruebas -->
                                <div class="mb-3">
                                    <label for="secIncident-{{ $shipment->stm_id }}" class="form-label">Sec Incident</label>
                                    <select class="form-select" id="secIncident-{{ $shipment->stm_id }}" name="sec_incident" disabled data-original="null">
                                        <option value="null" selected>No Incident</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="incidentType-{{ $shipment->stm_id }}" class="form-label">Incident Type</label>
                                    <input type="text" class="form-control" id="incidentType-{{ $shipment->stm_id }}" name="incident_type" disabled placeholder="Type of incident (if any)" data-original="">
                                </div>
                                <div class="mb-3">
                                    <label for="incidentDate-{{ $shipment->stm_id }}" class="form-label">Incident Date</label>
                                    <input type="datetime-local" class="form-control" id="incidentDate-{{ $shipment->stm_id }}" name="incident_date" disabled data-original="">
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
                                    <p>{{ $shipment->stm_id ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reference</label>
                                    <p>{{ $shipment->reference ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bonded</label>
                                    <p>{{ $shipment->bonded ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Origin</label>
                                    <p>{{ $shipment->company->CoName ?? 'Origin not available' }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Destination</label>
                                    <p>{{ $shipment->companydest->CoName ?? 'Not available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pre-Alerted Date & Time</label>
                                    <p>{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trailer ID</label>
                                    <p>{{ $shipment->id_trailer ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    <p>{{ $shipment->company->CoName ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trailer</label>
                                    <p>{{ $shipment->trailer ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Truck</label>
                                    <p>{{ $shipment->truck ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Driver ID</label>
                                    <p>{{ $shipment->id_driver ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ETD (Estimated Time of Departure)</label>
                                    <p>{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Units</label>
                                    <p>{{ $shipment->units ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pallets</label>
                                    <p>{{ $shipment->pallets ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Seal 1</label>
                                    <p>{{ $shipment->seal1 ?? 'Not Available' }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Seal 2</label>
                                    <p>{{ $shipment->seal2 ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Security Company ID</label>
                                    <p>{{ $shipment->security_company_id ?? 'Not Available' }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Security Company</label>
                                    <p>{{ $shipment->securityCompany->gntc_description ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tracker 1</label>
                                    <p>{{ $shipment->tracker1 ?? 'Not Available' }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tracker 2</label>
                                    <p>{{ $shipment->tracker2 ?? 'Not Available' }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tracker 3</label>
                                    <p>{{ $shipment->tracker3 ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secondary Shipment ID</label>
                                    <p>{{ $shipment->secondary_shipment_id ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Driver Assigned Date</label>
                                    <p>{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pick-Up Date</label>
                                    <p>{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">In Transit Date</label>
                                    <p>{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secured Yard Date</label>
                                    <p>{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Current Status</label>
                                    <p>{{ $shipment->currentStatus->gntc_description ?? 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Shipment Type </label>
                                    <p>{{ $shipment->shipmentType->gntc_description ?? 'Not Availible' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Delivered Date</label>
                                    <p>{{ $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">At Door Date</label>
                                    <p>{{ $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Approved ETA date & Time</label>
                                    <p>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Late Reason</label>
                                    <p>{{ $shipment->lateReason->value ?? 'Not Available' }}</p>
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

        let form = $(this);
        let formAction = form.attr('action');
        let formData = form.serialize();

        let lane = form.find('[name="lane"]').val(); // Aseguramos que buscamos dentro del formulario específico
        let pickUpDate = form.find('[name="pick_up_date"]').val();
        let inTransitDate = form.find('[name="intransit_date"]').val();
        let reference = form.find('[name="reference"]').val(); // Obtener referencia

        let warningMessage = "";

        // Depuración para ver las fechas
        console.log('Pick Up Date:', pickUpDate);
        console.log('In Transit Date:', inTransitDate);

        // Validación L2-B
        if (lane === 'L2-B' && pickUpDate && inTransitDate) {
            const pickUp = new Date(pickUpDate);
            const inTransit = new Date(inTransitDate);

            console.log('pickUp:', pickUp);
            console.log('inTransit:', inTransit);

            // Comparar solo la fecha (día, mes, año)
            if (pickUp.toISOString().split('T')[0] !== inTransit.toISOString().split('T')[0]) {
                warningMessage = 'The Picked Up Date does not match the In Transit Date for this shipment, Are you sure to save?.';
                console.log('Warning:', warningMessage); // Verifica si se asignó el mensaje
            }
        }

        // Nueva validación para L1-C comparando con el envío anterior con L1-B
        if (lane === 'L1-C' && reference) {
            console.log('Buscando envío anterior con referencia:', reference, 'y lane L1-B');
            let shipmentpkId = form.find('[name="pk_shipment"]').val();  // Ahora obtiene el valor del campo oculto pk_shipment
            console.log('shipmentpkId:', shipmentpkId);

            $.ajax({
                url: '/path/to/previous-shipment/' + shipmentpkId, // Incluimos el pk_shipment en la URL
                method: 'GET',
                data: {
                    reference: reference,  // Pasamos la referencia para identificar el envío
                },
                success: function (response) {
                    console.log('Respuesta del servidor:', response);
                    if (response.previousShipment) {
                        // Compara solo el día de la fecha "pick_up_date"
                        let previousShipment = response.previousShipment;
                        let previousPickUpDate = new Date(previousShipment.pick_up_date); // Convertir a objeto Date
                        let currentPickUpDate = new Date(pickUpDate); // Convertir a objeto Date

                        // Obtener el día (sin considerar el mes, horas, minutos, etc.)
                        let previousPickUpDay = previousPickUpDate.getDate();
                        let currentPickUpDay = currentPickUpDate.getDate();

                        // Verificar si los días de pick_up_date no coinciden
                        if (currentPickUpDay !== previousPickUpDay) {
                            warningMessage = 'The Picked Up Date does not match the previous L1-B event, Are you sure to save?.';
                            Swal.fire({
                                title: 'Warning',
                                text: warningMessage,
                                icon: 'warning',
                                confirmButtonText: 'Continue'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    sendFormData(formAction, formData); // Enviar el formulario si el usuario acepta
                                }
                            });
                        } else {
                            sendFormData(formAction, formData); // Si los días coinciden, enviar el formulario
                        }
                    } else {
                        // Si no se encuentra el envío anterior, continuar con el envío
                        sendFormData(formAction, formData);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo obtener el envío anterior.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });

            return; // Evitar que el formulario se envíe hasta que tengamos la respuesta de la validación
        }

        // Si no hay advertencia, enviamos el formulario
        if (warningMessage) {
            Swal.fire({
                title: 'Warning',
                text: warningMessage,
                icon: 'warning',
                confirmButtonText: 'Continue'
            }).then((result) => {
                if (result.isConfirmed) {
                    sendFormData(formAction, formData); // Enviar el formulario si el usuario acepta
                }
            });
        } else {
            // Si no hay advertencia, enviamos el formulario
            sendFormData(formAction, formData);
        }
    });

    // Función para enviar los datos del formulario
    function sendFormData(formAction, formData) {
        $.ajax({
            url: formAction,
            method: 'POST', // Laravel maneja PUT con _method en datos
            data: formData + "&_method=PUT", // Asegura compatibilidad con Laravel
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
                    title: '¡Success!',
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
    }
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
    const cardsContainer = document.getElementById("cardsContainer");
    const cardElements = cardsContainer.querySelectorAll(".card");
    const $activeFilterDiv = $('#activeFilterDiv');
    const $activeFilterText = $('#activeFilterText');
    const closeActiveFilterButton = document.getElementById("closeActiveFilter");

    // 🔹 Función para obtener los valores de los checkboxes seleccionados
    function getSelectedStatuses() {
        return Array.from(document.querySelectorAll(".status-filter:checked")).map(checkbox => checkbox.value.toLowerCase());
    }

    // 🔹 Función para aplicar filtros de status (SOLO cuando se presiona "Apply")
    function applyCheckboxFilter() {
        const selectedStatuses = getSelectedStatuses();

        console.log("Estados seleccionados (Apply presionado):", selectedStatuses);

        if (selectedStatuses.length > 0) {
            cardElements.forEach(card => {
                const cardStatus = card.querySelector(".status") ? card.querySelector(".status").textContent.toLowerCase() : "";
                card.style.display = selectedStatuses.includes(cardStatus) ? "" : "none";
            });

            $activeFilterText.text("Filter: Status - " + selectedStatuses.join(", "));
            $activeFilterDiv.show();
        } else {
            resetFilters();
        }
    }

    // 🔹 Función para resetear todos los filtros
    function resetFilters() {
        cardElements.forEach(card => card.style.display = "");  // Mostrar todas las tarjetas
        document.querySelectorAll('input[type="text"]').forEach(input => input.value = "");  // Limpiar los inputs de texto
        document.querySelectorAll(".status-filter").forEach(checkbox => checkbox.checked = false);  // Desmarcar los checkboxes
        $activeFilterDiv.hide();
    }

    // 🔹 Evento para cerrar el filtro al hacer clic en "X"
    if (closeActiveFilterButton) {
        closeActiveFilterButton.addEventListener("click", function () {
            resetFilters();
            console.log("Filtro eliminado");
        });
    }

    // 🔹 Evento para el botón "Apply" de status (Solo filtra cuando se presiona)
    const applyStatusButton = document.getElementById("applystatusidfilter");
    if (applyStatusButton) {
        applyStatusButton.addEventListener("click", applyCheckboxFilter);
    }

    // 🔹 Evento para el botón de refresh
    const refreshButton = document.getElementById("refreshshipmentstable");
    if (refreshButton) {
        refreshButton.addEventListener("click", function () {
            resetFilters();
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
            'Dock Door': 'Dock Door',
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
    const offcanvasUpdateStatusElements = document.querySelectorAll("[id^='offcanvasUpdateStatus-']");

    offcanvasUpdateStatusElements.forEach(offcanvasUpdateStatus => {
        // Al abrir el offcanvas, guardar los valores actuales de los campos de fecha y otros
        offcanvasUpdateStatus.addEventListener("shown.bs.offcanvas", function () {
            const dateInputs = offcanvasUpdateStatus.querySelectorAll("input[type='date'], input[type='datetime-local'], .flatpickr");
            const selectInputs = offcanvasUpdateStatus.querySelectorAll("select");

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

        // Al cerrar el offcanvas, restaurar los valores originales
        offcanvasUpdateStatus.addEventListener("hidden.bs.offcanvas", function () {
            const inputs = offcanvasUpdateStatus.querySelectorAll("input, select, textarea, .flatpickr");
            inputs.forEach(input => {
                if (input.dataset.original !== undefined) {
                    input.value = input.dataset.original; // Restaurar el valor original
                }
            });
        });
    });
    });
</script>

<script>
 // JavaScript para cambiar de pestaña al hacer clic en "Next"
 document.getElementById('nextButton').addEventListener('click', function () {
    // Obtener el STM ID dinámicamente
    var stmId = '{{ $shipment->stm_id ?? '' }}';

    // Obtener la siguiente pestaña basada en el STM ID
    var nextTab = document.getElementById('pills-update-status-tab-' + stmId);

    if (nextTab) {
        // Cambiar a la siguiente pestaña usando Bootstrap
        var tab = new bootstrap.Tab(nextTab);
        tab.show(); // Muestra la siguiente pestaña
    }
    });
</script>


<script>
    function validateShipment(stm_id) {
        const unitsInput = document.getElementById('units-' + stm_id);
        const palletsInput = document.getElementById('pallets-' + stm_id);
        const errorMessage = document.getElementById('error-message-' + stm_id);

        const units = parseInt(unitsInput.value.trim());
        const pallets = parseInt(palletsInput.value.trim());

        if (isNaN(units) || units <= 0) {
            errorMessage.textContent = "Units cannot be empty or 0.";
            errorMessage.style.display = "block";
            unitsInput.classList.add('is-invalid');
            return false;
        } else {
            unitsInput.classList.remove('is-invalid');
        }

        if (isNaN(pallets) || pallets <= 0) {
            errorMessage.textContent = "Pallets cannot be empty or 0.";
            errorMessage.style.display = "block";
            palletsInput.classList.add('is-invalid');
            return false;
        } else {
            palletsInput.classList.remove('is-invalid');
        }

        if (pallets > units) {
            errorMessage.textContent = "Pallets cannot be greater than Units.";
            errorMessage.style.display = "block";
            palletsInput.classList.add('is-invalid');
            return false;
        } else {
            palletsInput.classList.remove('is-invalid');
        }

        errorMessage.style.display = "none";
        return true;
    }
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Al cargar la página, deshabilitar los campos con valores existentes, pero sin perder sus valores
    document.querySelectorAll(".flatpickr").forEach(function (input) {
        if (input.value.trim() !== "" && input.name !== "etd") {  // Excluir el campo 'etd'
            // Crear un campo oculto para almacenar el valor original
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = input.name;
            hiddenInput.value = input.value.trim();
            input.parentElement.appendChild(hiddenInput); // Añadir el campo oculto al formulario

            input.setAttribute("disabled", "disabled"); // Deshabilitar el campo
        }
    });

    // Cuando el usuario cambia un campo, removemos la deshabilitación
    document.querySelectorAll(".flatpickr").forEach(function (input) {
        input.addEventListener("input", function () {
            if (input.value.trim() !== "" && input.name !== "etd") {  // Excluir el campo 'etd'
                input.removeAttribute("disabled"); // Habilitar el campo si tiene valor
            }
        });
    });

    // Al hacer clic en "guardar", deshabilitar campos que tienen valor y no permitir cambios
    document.getElementById("saveButton").addEventListener("click", function () {
        document.querySelectorAll(".flatpickr").forEach(function (input) {
            if (input.value.trim() !== "" && input.name !== "etd") {  // Excluir el campo 'etd'
                input.setAttribute("disabled", "disabled"); // Deshabilitar el campo
            }
        });
    });
});

// Evitar que los valores de los campos deshabilitados se pierdan al hacer submit
document.querySelector("form").addEventListener("submit", function (event) {
    document.querySelectorAll(".flatpickr").forEach(function (input) {
        if (input.disabled) {
            let hiddenInput = document.querySelector(`input[name="${input.name}"]`);
            if (hiddenInput) {
                hiddenInput.value = input.value; // Mantener el valor original al hacer submit
            }
        }
    });
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Buscar todos los offcanvas (uno por cada shipment)
        const offcanvases = document.querySelectorAll('.offcanvas');

        offcanvases.forEach(offcanvas => {
            offcanvas.addEventListener('shown.bs.offcanvas', function () {
                const shipmentId = this.id.split('-')[1]; // Extrae el ID desde el ID del offcanvas

                const currentStatusSelect = document.getElementById(`currentStatus-${shipmentId}`);
                const lateReasonField = document.getElementById(`late_reason-${shipmentId}`);

                if (!currentStatusSelect || !lateReasonField) return;

                const currentStatusValue = currentStatusSelect.options[currentStatusSelect.selectedIndex].text.trim().toLowerCase();

                // Deshabilitar si el texto del status es "delivered" o "finalized"
                if (['delivered', 'finalized'].includes(currentStatusValue)) {
                    lateReasonField.disabled = true;
                } else {
                    lateReasonField.disabled = false;
                }

                // También puedes actualizar dinámicamente si cambia el select
                currentStatusSelect.addEventListener('change', function () {
                    const updatedStatus = this.options[this.selectedIndex].text.trim().toLowerCase();
                    if (['delivered', 'finalized'].includes(updatedStatus)) {
                        lateReasonField.disabled = true;
                    } else {
                        lateReasonField.disabled = false;
                    }
                });
            });
        });
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
