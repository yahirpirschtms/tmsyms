@extends('layouts.app-master')

@section('title', 'All Shipments')

@section('content')
    @auth
    <div class="container my-4">
        <!-- Título centrado -->
        <div class="d-flex justify-content-center my-4">
            <h2 class="gradient-text text-capitalize fw-bolder">All Shipments</h2>
        </div>

        <!-- Tabla dentro de un contenedor responsivo -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
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
                        <th>Update Shipment Status</th>
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
                        <td>{{ $shipment->origin }}</td>
                        <td>{{ $shipment->id_trailer }}</td>
                        <td>{{ $shipment->destination }}</td>
                        <td>{{ $shipment->pre_alerted_datetime }}</td>
                        <td>{{ $shipment->carrier_dropping_trailer }}</td>
                        <td>{{ $shipment->trailer_owner }}</td>
                        <td>{{ $shipment->driver_truck }}</td>
                        <td>{{ $shipment->suggested_delivery_date }}</td>
                        <td>{{ $shipment->units }}</td>
                        <td>{{ $shipment->pallets }}</td>
                        <td>{{ $shipment->security_seals }}</td>
                        <td>{{ $shipment->notes }}</td>
                        <td>{{ $shipment->gnct_id_current_status }}</td>
                        <td>{{ $shipment->driver_assigned_date }}</td>
                        <td>{{ $shipment->pick_up_date }}</td>
                        <td>{{ $shipment->intransit_date }}</td>
                        <td>{{ $shipment->delivered_date }}</td>
                        <td>{{ $shipment->secured_yarddate }}</td>
                        <td>{{ $shipment->approved_eta_date }}</td>
                        <td>{{ $shipment->approved_eta_time }}</td>
                        <td>{{ $shipment->sec_incident }}</td>
                        <td>{{ $shipment->incident_type }}</td>
                        <td>{{ $shipment->incident_date }}</td>
                        <td>{{ $shipment->incident_notes }}</td>
                        <td>{{ $shipment->wh_status }}</td>
                        <td>{{ $shipment->at_door_time }}</td>
                        <td>{{ $shipment->offload_time }}</td>
                        <td>{{ $shipment->billing_date }}</td>
                        <td>{{ $shipment->billing_id }}</td>
                        <td>{{ $shipment->device_number }}</td>
                        <td>{{ $shipment->overhaul_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection
