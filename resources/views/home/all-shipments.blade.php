@extends('layouts.app-master')

@section('title', 'All Shipments')

@section('content')
    @auth
    <div class="container my-4">
        <!-- Título centrado -->
        <div class="d-flex justify-content-center my-4">
            <h2 class="gradient-text text-capitalize fw-bolder">All Shipments</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
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
                        <th>Approved ETA Date</th>
                        <th>Approved ETA Time</th>
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
                    <tr>
                        <td>{{ $shipment->gnct_id_shipment_type }}</td>
                        <td>{{ $shipment->stm_id }}</td>
                        <td>{{ $shipment->secondary_shipment_id }}</td>
                        <td>{{ $shipment->reference }}</td>
                        <td>{{ $shipment->originCatalog->gntc_value ?? 'Origen no disponible' }}</td>
                        <td>{{ $shipment->id_trailer }}</td>
                        <td>{{ $shipment->destination }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('d/m/Y H:i') ?? 'No disponible' }}</td>
                        <td>{{ $shipment->carrier_dropping_trailer }}</td>
                        <td>{{ $shipment->trailer_owner }}</td>
                        <td>{{ $shipment->driver_truck }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->suggested_delivery_date)->format('d/m/Y') }}</td>
                        <td>{{ $shipment->units }}</td>
                        <td>{{ $shipment->pallets }}</td>
                        <td>{{ $shipment->security_seals }}</td>
                        <td>{{ $shipment->notes }}</td>
                        <td>{{ $shipment->currentStatus->gntc_value ?? 'Estado no disponible' }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->pick_up_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->intransit_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->delivered_date)->format('d/m/Y') }}</td>
                        <td>{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('d/m/Y H:i') : 'No disponible' }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->approved_eta_date)->format('d/m/Y') }}</td>
                        <td>{{ $shipment->approved_eta_time }}</td>
                        <td>{{ $shipment->sec_incident }}</td>
                        <td>{{ $shipment->incident_type }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->incident_date)->format('d/m/Y') }}</td>
                        <td>{{ $shipment->incident_notes }}</td>
                        <td>{{ $shipment->wh_status }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->at_door_time)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->offload_time)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($shipment->billing_date)->format('d/m/Y') }}</td>
                        <td>{{ $shipment->billing_id }}</td>
                        <td>{{ $shipment->device_number }}</td>
                        <td>{{ $shipment->overhaul_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection
