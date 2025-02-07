@extends('layouts.app-master')

@section('title', 'All Shipments')

@section('content')
    @auth
    <div class="container my-4">
        <!-- Título centrado -->
        <div class="d-flex justify-content-center my-4">
            <h2 class="gradient-text text-capitalize fw-bolder">All Shipments</h2>
        </div>
         <!-- Botones Añadir y Refresh -->
         <div class="d-flex justify-content-end mt-4 mb-2">
            <!-- Search Input for All Shipments -->
            <div style="position: relative; display: inline-block; width: 100%;" class="me-4">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #6c757d; cursor: pointer;" onclick="document.getElementById('searchByShipment').focus()"></i>
                <input class="form-control" type="search" placeholder="    Search WH Appoinment Viewer" name="searchByShipment" id="searchByShipment" aria-label="Search" style="padding-left: 30px;">
            </div>

            <!-- Export Button -->
            <button type="button" class="btn me-2 btn-success" id="exportfile" data-bs-toggle="tooltip" data-bs-placement="top" title="Export File">
                <i class="fa-solid fa-file-export"></i>
            </button>

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
                    <!-- Filtro por Shipment Type -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyshipmenttypefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsshipmenttypefilter" aria-expanded="false" aria-controls="multiCollapsshipmenttypefilter">Shipment Type</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsshipmenttypefilter">
                                    <input type="text" class="form-control" id="inputapplyshipmenttypefilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyshipmenttypefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por STM ID -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplystmfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsstmfilter" aria-expanded="false" aria-controls="multiCollapsstmfilter">STM ID</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsstmfilter">
                                    <input type="text" class="form-control" id="inputapplystmfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applystmfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Secondary Shipment ID -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplysecondaryshipmentidfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapssecondaryshipmentidfilter" aria-expanded="false" aria-controls="multiCollapssecondaryshipmentidfilter">Secondary Shipment ID</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapssecondaryshipmentidfilter">
                                    <input type="text" class="form-control" id="inputapplysecondaryshipmentidfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applysecondaryshipmentidfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Landstar Reference -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplylandstarreferencefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapslandstarreferencefilter" aria-expanded="false" aria-controls="multiCollapslandstarreferencefilter">Landstar Reference</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapslandstarreferencefilter">
                                    <input type="text" class="form-control" id="inputapplylandstarreferencefilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applylandstarreferencefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Origin -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyoriginfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsoriginfilter" aria-expanded="false" aria-controls="multiCollapsoriginfilter">Origin</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsoriginfilter">
                                    <input type="text" class="form-control" id="inputapplyoriginfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyoriginfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Trailer ID -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplytraileridfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapstraileridfilter" aria-expanded="false" aria-controls="multiCollapstraileridfilter">Trailer ID</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapstraileridfilter">
                                    <input type="text" class="form-control" id="inputapplytraileridfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applytraileridfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Destination -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydestinationfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsdestinationfilter" aria-expanded="false" aria-controls="multiCollapsdestinationfilter">Destination</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsdestinationfilter">
                                    <input type="text" class="form-control" id="inputdestinationfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydestinationfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Pre-Alert Date & Time -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyprealertfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsprealertfilter" aria-expanded="false" aria-controls="multiCollapsprealertfilter">Pre-Alert Date & Time</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsprealertfilter">
                                    <input type="text" class="form-control datetimepicker" id="inputapplyprealertfilter" placeholder="Select Date">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyprealertfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Carrier Dropping Trailer -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplycarrierfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapscarrierfilter" aria-expanded="false" aria-controls="multiCollapscarrierfilter">Carrier Dropping Trailer</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapscarrierfilter">
                                    <input type="text" class="form-control" id="inputapplycarrierfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applycarrierfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Trailer Owner -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplytrailerownerfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapstrailerownerfilter" aria-expanded="false" aria-controls="multiCollapstrailerownerfilter">Trailer Owner</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapstrailerownerfilter">
                                    <input type="text" class="form-control" id="inputapplytrailerownerfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applytrailerownerfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Driver & Truck -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydrivertruckfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsdrivertruckfilter" aria-expanded="false" aria-controls="multiCollapsdrivertruckfilter">Driver & Truck</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsdrivertruckfilter">
                                    <input type="text" class="form-control" id="inputapplydrivertruckfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydrivertruckfilter">Apply</button>
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
                <div class="table-responsive">
                <table class="table" id="shipmentsTable">
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
                            <th>Driver</th>
                            <th>Truck</th>
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
                            <!--<th>Date of Billing</th>  -->
                            <!--<th>Billing ID</th>  -->
                            <th>Device Number</th>
                            <th>Overhaul ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                        <tr data-bs-toggle="modal" data-bs-target="#shipmentModal{{ $shipment->stm_id }}" class="clickable-row" data-shipment-id="{{ $shipment->stm_id }}">
                            <td>{{ $shipment->shipmentType->gntc_description ?? 'Not available' }}</td>
                            <td>{{ $shipment->stm_id ?? 'Not available' }}</td>
                            <td>{{ $shipment->secondary_shipment_id ?? 'Not available' }}</td>
                            <td>{{ $shipment->reference ?? 'Not available' }}</td>
                            <td>
                                {{ optional($companies->firstWhere('pk_company', $shipment->origin))->CoName ?? 'Origin not available' }}
                            </td>
                            <td>{{ $shipment->id_trailer ?? 'Not available' }}</td>
                            <td>
                                {{ optional($companies->firstWhere('pk_company', $shipment->destination))->CoName ?? 'Not available' }}
                            </td>
                            <td>{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') : 'Not available' }}</td>
                            <td>{{ $shipment->company->CoName ?? 'Not available' }}</td>
                            <td>{{ $shipment->company->CoName ?? 'Not available' }}</td>
                            <td>{{ $shipment->driver->drivername ?? 'Not available' }}</td>
                            <td>{{ $shipment->truck ?? 'Not available' }}</td>
                            <td>{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y ') : 'Not available' }}</td>
                            <td>{{ $shipment->units ?? 'Not available' }}</td>
                            <td>{{ $shipment->pallets ?? 'Not available' }}</td>
                            <td>{{ $shipment->security_seals ?? 'Not available' }}</td>
                            <td>{{ $shipment->notes ?? 'Not available' }}</td>
                            <td>{{ $shipment->currentStatus->gntc_value ?? 'Status not available' }}</td>
                            <td>{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i:s') : 'Not available' }}</td>
                            <td>{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i:s') : 'Not available' }}</td>
                            <td>{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i:s') : 'Not available' }}</td>
                            <td>{{ $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y H:i:s') : 'Not available' }}</td>
                            <td>{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i:s') : 'Not available' }}</td>
                            <td>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i:s') : 'Not available' }}</td>
                            <td>{{ $shipment->sec_incident ?? 'Not available' }}</td>
                            <td>{{ $shipment->incident_type ?? 'Not available' }}</td>
                            <td>{{ $shipment->incident_date ? \Carbon\Carbon::parse($shipment->incident_date)->format('m/d/Y H:i:s') : 'Not available' }}</td>
                            <td>{{ $shipment->incident_notes ?? 'Not available' }}</td>
                            <td>{{ $shipment->wh_status ?? 'Not available' }}</td>
                            <td>{{ $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('H:i') : 'Not available' }}</td>
                            <td>{{ $shipment->offloading_time ? \Carbon\Carbon::parse($shipment->offloading_time)->format('H:i') : 'Not available' }}</td>
                            <td>{{ $shipment->device_number ?? 'Not available' }}</td>
                            <td>{{ $shipment->overhaul_id ?? 'Not available' }}</td>
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
                    <div class="modal-header" style="background-color: #0056b3;" >
                        <h5 class="modal-title" id="shipmentModalLabel{{ $shipment->stm_id }}">Shipment Details - {{ $shipment->stm_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Pestañas de detalle -->
                        <ul class="nav nav-pills mb-3" id="pills-tab{{ $shipment->stm_id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-shipment-details-tab{{ $shipment->stm_id }}"
                                   data-bs-toggle="pill" href="#pills-shipment-details{{ $shipment->stm_id }}"
                                   role="tab" aria-controls="pills-shipment-details{{ $shipment->stm_id }}"
                                   aria-selected="true"> Initial Shipment Info</a>
                            </li>
                            <li class="nav-item mx-2" role="presentation">
                                <a class="nav-link" id="pills-update-status-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-update-status{{ $shipment->stm_id }}" role="tab" aria-controls="pills-update-status{{ $shipment->stm_id }}" aria-selected="false">Update Shipment Status</a>
                            </li>
                            <li class="nav-item mx-2" role="presentation">
                                <a class="nav-link" id="pills-shipment-info-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-shipment-info-{{ $shipment->stm_id }}" role="tab" aria-controls="pills-shipment-info-{{ $shipment->stm_id }}" aria-selected="false">Shipment Details</a>
                            </li>
                        </ul>

                        <!-- Contenedor de contenido -->
                    <form id="shipmentForm-{{ $shipment->stm_id }}" method="POST" action="/update-status-endpoint/{{ $shipment->pk_shipment }}" onsubmit="return validateShipment('{{ $shipment->stm_id }}')">
                        <div class="tab-content" id="pills-tabContent{{ $shipment->stm_id }}">
                            <div class="tab-pane fade show active" id="pills-shipment-details{{ $shipment->stm_id }}"
                                role="tabpanel" aria-labelledby="pills-shipment-details-tab{{ $shipment->stm_id }}">

                            <div class="mb-3">
                                <label for="stm_id" class="form-label">STM ID</label>
                                <input type="text" class="form-control" id="stm_id" value="{{ $shipment->stm_id ?? 'STM ID no disponible' }}" readonly data-original="{{ $shipment->stm_id ?? 'STM ID no disponible' }}">
                            </div>

                            <div class="mb-3">
                                <label for="device_number" class="form-label">Device Number</label>
                                <input type="text" class="form-control" id="device_number" name="device_number" value="{{ $shipment->device_number }}" data-original="{{ $shipment->device_number }}">
                            </div>

                            <div class="mb-3">
                                <label for="overhaul_id" class="form-label">Overhaul ID</label>
                                <input type="text" class="form-control" id="overhaul_id" name="overhaul_id" value="{{ $shipment->overhaul_id }}" data-original="{{ $shipment->overhaul_id }}">
                            </div>

                            <div class="mb-3">
                                <label for="secondary_shipment_id" class="form-label">Secondary Shipment ID</label>
                                <input type="text" class="form-control" id="secondary_shipment_id" name="secondary_shipment_id" value="{{ $shipment->secondary_shipment_id }}" data-original="{{ $shipment->secondary_shipment_id }}">
                            </div>

                            <div class="mb-3">
                                <label for="reference" class="form-label">Landstar Reference</label>
                                <input type="text" class="form-control" id="reference" name="reference" value="{{ $shipment->reference }}" data-original="{{ $shipment->reference }}">
                            </div>

                            <div class="mb-3">
                                <label for="shipment_type" class="form-label">Shipment Type</label>
                                <input type="text" class="form-control" id="shipment_type" name="shipment_type" value="{{ $shipment->shipmentType->gntc_description ?? 'No disponible' }}" data-original="{{ $shipment->shipmentType->gntc_description ?? 'No disponible' }}">
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
                                <select class="form-select" id="origin-{{ $shipment->stm_id }}" name="origin" data-original="{{ $shipment->origin }}">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->pk_company }}"
                                            {{ old("origin-{$shipment->stm_id}", $shipment->origin) == $company->pk_company ? 'selected' : '' }}>
                                            {{ $company->CoName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Destination -->
                            <div class="mb-3">
                                <label for="destination-{{ $shipment->stm_id }}" class="form-label">Destination</label>
                                <select class="form-select" id="destination-{{ $shipment->stm_id }}" name="destination" data-original="{{ $shipment->destination }}">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->pk_company }}"
                                            {{ old("destination-{$shipment->stm_id}", $shipment->destination) == $company->pk_company ? 'selected' : '' }}>
                                            {{ $company->CoName }}
                                        </option>
                                    @endforeach
                                </select>
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
                                        <option value="{{ $driver->id_driver }}"
                                            {{ old('id_driver', $shipment->id_driver) == $driver->id_driver ? 'selected' : '' }}>
                                            {{ $driver->drivername }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="Truck" class="form-label">Truck</label>
                                <input type="text" class="form-control" id="Truck" name="units" value="{{ $shipment->truck }}" data-original="{{ $shipment->truck }}">
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
                                <label for="security_seals" class="form-label">Security Seal</label>
                                <input type="text" class="form-control" id="security_seals" name="security_seals" value="{{ $shipment->security_seals }}" data-original="{{ $shipment->security_seals }}">
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" data-original="{{ $shipment->notes }}">{{ $shipment->notes }}</textarea>
                            </div>


                                <!-- Agrega más campos si es necesario -->
                                <button id="nextButton-{{ $shipment->stm_id }}" class="btn btn-primary" type="button">Next</button>
                            </div>
                            <div class="tab-pane fade" id="pills-update-status{{ $shipment->stm_id }}"
                                role="tabpanel" aria-labelledby="pills-update-status-tab{{ $shipment->stm_id }}">
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
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                    </form>


                            </div>
                            <div class="tab-pane fade"
                                id="pills-shipment-info-{{ $shipment->stm_id }}"
                                role="tabpanel"
                                aria-labelledby="pills-shipment-info-tab{{ $shipment->stm_id }}">
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
    document.addEventListener("DOMContentLoaded", function () {
      const tableRows = document.querySelectorAll("#shipmentsTable tbody tr");  // Filas de la tabla

      // Función común para aplicar filtros
      function applyFilter(inputId, buttonId, columnIndex) {
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
                      $activeFilterText.text("Filtro: " + inputFilter.placeholder + ": " + filterValue);
                      $activeFilterDiv.show();

                      // Filtrar las filas de la tabla
                      tableRows.forEach(row => {
                          const cell = row.cells[columnIndex];  // Columna correspondiente

                          if (cell) {
                              const cellText = cell.textContent || cell.innerText;  // Obtener texto de la celda
                              if (cellText.toLowerCase().includes(filterValue)) {
                                  row.style.display = "";  // Mostrar la fila si coincide con el filtro
                              } else {
                                  row.style.display = "none";  // Ocultar la fila si no coincide
                              }
                          }
                      });
                  } else {
                      // Si no hay valor en el filtro, mostrar todas las filas
                      tableRows.forEach(row => row.style.display = "");
                      $activeFilterDiv.hide();  // Ocultar la sección de filtro aplicado si no hay filtro
                  }
              });
          }

          // Lógica para cerrar el filtro y resetear la tabla al hacer clic en la "X"
          if ($closeActiveFilterButton) {
              $closeActiveFilterButton.on('click', function () {
                  // Limpiar el campo de filtro y mostrar todas las filas
                  inputFilter.value = "";
                  tableRows.forEach(row => row.style.display = "");

                  // Ocultar la sección de filtro aplicado
                  $activeFilterDiv.hide();
              });
          }
      }

      // Llamar a la función de filtro para cada uno de los filtros
      applyFilter('inputapplyshipmenttypefilter', 'applyshipmenttypefilter', 0);  // Filtro por Shipment Type
      applyFilter('inputapplystmfilter', 'applystmfilter', 1);  // Filtro por STM ID
      applyFilter('inputapplysecondaryshipmentidfilter', 'applysecondaryshipmentidfilter', 2);  // Filtro por Secondary Shipment ID
      applyFilter('inputapplylandstarreferencefilter', 'applylandstarreferencefilter', 3);  // Filtro por Landstar Reference
      applyFilter('inputapplyoriginfilter', 'applyoriginfilter', 4);  // Filtro por Origin
      applyFilter('inputapplytraileridfilter', 'applytraileridfilter', 5);  // Filtro por Trailer ID
      applyFilter('inputdestinationfilter', 'applydestinationfilter', 6);  // Filtro por Destination
      applyFilter('inputapplyprealertfilter', 'applyprealertfilter', 7);  // Filtro por Pre-Alert Date & Time
      applyFilter('inputapplycarrierfilter', 'applycarrierfilter', 8);  // Filtro por Carrier Dropping Trailer
      applyFilter('inputapplytrailerownerfilter', 'applytrailerownerfilter', 9);  // Filtro por Trailer Owner
      applyFilter('inputapplydrivertruckfilter', 'applydrivertruckfilter', 10);  // Filtro por Driver & Truck
      applyFilter('inputapplypickupfilter', 'applypickupfilter', 11);  // Filtro por Pick-up Location

      // Evento para el botón de refresh
    const refreshButton = document.getElementById("refreshshipmentstable");
    if (refreshButton) {
        refreshButton.addEventListener("click", function () {
            // Recargar la tabla, por ejemplo, mostrando todas las filas y limpiando los filtros
            tableRows.forEach(row => row.style.display = "");  // Mostrar todas las filas
            const inputs = document.querySelectorAll('input');  // Obtener todos los campos de filtro
            inputs.forEach(input => input.value = "");  // Limpiar los filtros
            $('#activeFilterDiv').hide();  // Ocultar la sección del filtro activo
            console.log("Tabla recargada");
        });
    }
  });
</script>

<script>
    document.getElementById('exportfile').addEventListener('click', function () {
        // Obtén la tabla con el id "shipmentsTable"
        var table = document.getElementById('shipmentsTable');

        // Convierte la tabla HTML en una hoja de cálculo de Excel
        var wb = XLSX.utils.table_to_book(table, { sheet: "Shipments" });

        // Aplica formato a las columnas de fechas
        var ws = wb.Sheets["Shipments"];

        // Recorre todas las filas y aplica formato a las columnas de fechas
        var range = XLSX.utils.decode_range(ws['!ref']); // Obtiene el rango de la hoja
        for (var row = range.s.r + 1; row <= range.e.r; row++) {
            // Lista de columnas que contienen fechas (índices)
            var dateColumns = [7, 11, 16, 17, 18, 19, 20, 21, 24, 26, 27];

            // Recorre cada columna de fechas y aplica el formato
            dateColumns.forEach(function(colIndex) {
                var cellAddress = { r: row, c: colIndex };
                var cell = ws[XLSX.utils.encode_cell(cellAddress)];
                if (cell) {
                    cell.z = "yyyy-mm-dd hh:mm:ss"; // El formato de fecha y hora
                }
            });
        }

        // Obtén la fecha y hora actuales
        var now = new Date();
        var year = now.getFullYear();
        var month = String(now.getMonth() + 1).padStart(2, '0'); // Mes con 2 dígitos
        var day = String(now.getDate()).padStart(2, '0');       // Día con 2 dígitos
        var hours = String(now.getHours()).padStart(2, '0');    // Horas con 2 dígitos
        var minutes = String(now.getMinutes()).padStart(2, '0');// Minutos con 2 dígitos
        var seconds = String(now.getSeconds()).padStart(2, '0');// Segundos con 2 dígitos

        // Formato: MM-DD-YYYY_HH-MM-SS
        var formattedDateTime = `${month}${day}${year}_${hours}-${minutes}-${seconds}`;

        // Define el nombre del archivo con fecha y hora
        var filename = `Shipments_${formattedDateTime}.xlsx`;

        // Exporta el archivo Excel
        XLSX.writeFile(wb, filename);
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


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[id^='nextButton-']").forEach(button => {
        button.addEventListener("click", function () {
            // Obtener el ID del shipment
            var shipmentId = button.id.replace("nextButton-", "");

            // Buscar la pestaña de destino
            var nextTab = document.querySelector("#pills-update-status-tab" + shipmentId);

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
    var stmId = '{{ $shipment->stm_id ?? '' }}';

    // Obtener la siguiente pestaña basada en el STM ID
    var nextTab = document.getElementById('pills-update-status-tab' + stmId);

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

@endsection

@section('custom-css')
<style>

  /* Cambiar fondo y texto */

/* Estilo para las pestañas */
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
</style>

