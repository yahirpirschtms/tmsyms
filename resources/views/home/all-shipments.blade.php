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
                            <!--<th>Date of Billing</th>  -->
                            <!--<th>Billing ID</th>  -->
                            <th>Device Number</th>
                            <th>Overhaul ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                        <tr data-bs-toggle="modal" data-bs-target="#shipmentModal{{ $shipment->stm_id }}" class="clickable-row" data-shipment-id="{{ $shipment->stm_id }}">
                            <td>{{ $shipment->shipmentType->gntc_description}}</td>
                            <td>{{ $shipment->stm_id }}</td>
                            <td>{{ $shipment->secondary_shipment_id }}</td>
                            <td>{{ $shipment->reference }}</td>
                            <td>{{ $shipment->company->CoName ?? 'Origen no disponible' }}</td>
                            <td>{{ $shipment->id_trailer }}</td>
                            <td>{{ $shipment->destinationFacility->fac_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') ?? 'No disponible' }}</td>
                            <td>{{ $shipment->carrier_dropping_trailer }}</td>
                            <td>{{ $shipment->trailer_owner }}</td>
                            <td>{{ $shipment->driver->drivername }} - {{ $shipment->truck }}</td>
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
                            <td>{{ \Carbon\Carbon::parse($shipment->offloading_time)->format('H:i') }}</td>
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
                    <div class="modal-header" style="background-color: #0056b3;" >
                        <h5 class="modal-title" id="shipmentModalLabel{{ $shipment->stm_id }}">Shipment Details - {{ $shipment->stm_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Pestañas de detalle -->
                        <ul class="nav nav-pills mb-3" id="pills-tab{{ $shipment->stm_id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-shipment-details-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-shipment-details{{ $shipment->stm_id }}" role="tab" aria-controls="pills-shipment-details{{ $shipment->stm_id }}" aria-selected="true">Shipment Details</a>
                            </li>
                            <li class="nav-item mx-2" role="presentation">
                                <a class="nav-link" id="pills-update-status-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-update-status{{ $shipment->stm_id }}" role="tab" aria-controls="pills-update-status{{ $shipment->stm_id }}" aria-selected="false">Initial Shipment Info</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="notes-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#notes{{ $shipment->stm_id }}" role="tab" aria-controls="notes{{ $shipment->stm_id }}" aria-selected="false">Update Shipment Status</a>
                            </li>
                        </ul>
                        </ul>
                        <div class="tab-content" style="border: none;" id="pills-tabContent{{ $shipment->stm_id }}">
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
                                        <input type="text" class="form-control" id="origin" value="{{ $shipment->company->CoName ?? 'Origen no disponible' }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="destination" class="form-label">Destination</label>
                                        <input type="text" class="form-control" id="destination" value="{{ $shipment->destinationFacility->fac_name }}" readonly>
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
                                        <input type="text" class="form-control" id="id_company" value="{{ $shipment->company->id_company  }}" readonly>
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
    $(document).on('submit', '#shipmentForm', function (event) {
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

