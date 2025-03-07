@extends('layouts.app-master')

@section('title', 'WH Appt Approval')

@section('content')
    @auth

    <script>//almacenar trailers
        let shipmentsData = @json($shipments->keyBy('pk_shipment'));
        console.log(shipmentsData);
    </script>

    <div id="encabezado y filtro" class="container  mt-4 " style=" background-color:white; position:fixed; left:0; right:0; top:80px; padding:10px; padding-bottom:0; z-index:10;" >
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" style="">WH Appoinment Approval</h2>
            </div>

            <!--Botones Añadir y refresh-->
            <div class="d-flex justify-content-end mt-4 mb-2">
                <div style="position: relative; display: inline-block; width: 100%;" class="me-4">
                    <i 
                        class="fa-solid fa-magnifying-glass" 
                        style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #6c757d; cursor: pointer;"
                        onclick="document.getElementById('searchemptytrailergeneralwh').focus()">
                    </i>
                    <input 
                        class="form-control" 
                        type="search" 
                        placeholder="    Search By Filters" 
                        name="searchemptytrailergeneralwh" 
                        id="searchemptytrailergeneralwh" 
                        aria-label="Search" 
                        style="padding-left: 30px;">
                </div>
                <button type="button" style="color: white;" class="btn me-2 btn-success" id="exportfile" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Export File">
                    <i class="fa-solid fa-file-export"></i>
                </button>
                <button type="button" style="color: white;" class="btn me-2 btn-primary" id="refreshwhetapprovaltable" data-url="{{ route('shipmentwh.data') }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Refresh Table">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </button>
                <button class="btn" id="addmorefiltersemptytrailer" style="color: white;background-color:orange;" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasaddmorefilters" aria-controls="offcanvasaddmorefilters">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>

            <div id="filtersapplied" class=" d-flex overflow-x-auto" style="scrollbar-width: none; margin:0">

                <div class="col-auto" id="emptytrailerfilterdivshipmenttype" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtnshipmenttype" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Shipment Type:</btn>
                        <input id="emptytrailerfilterinputshipmenttype" name="" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <input type="text" style="display: none;" name="emptytrailerfilterinputshipmenttypepk" id="emptytrailerfilterinputshipmenttypepk" value="">
                        <button id="emptytrailerfilterbuttonshipmenttype" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivshipmenttypecheckbox" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtnshipmenttypecheckbox" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Shipment Type:</btn>
                        <input id="emptytrailerfilterinputshipmenttypecheckbox" name="emptytrailerfilterinputshipmenttypecheckbox" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <input type="text" style="display:none" name="emptytrailerfilterinputshipmenttypecheckboxpk" id="emptytrailerfilterinputshipmenttypecheckboxpk" value="">
                        <button id="emptytrailerfilterbuttonshipmenttypecheckbox" style="border:unset; background-color:rgb(13, 82, 200); color:white; font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdividstm" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="mb-3 me-2">
                        <btn id="emptytrailerfilterbtnidstm" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">ID STM:</btn>
                        <input id="emptytrailerfilterinputidstm" name="emptytrailerfilterinputidstm" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonidstm" style="border:unset; background-color:rgb(13, 82, 200); color:white; font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivsecondaryid" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="mb-3 me-2">
                        <btn id="emptytrailerfilterbtnsecondaryid" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Secondary Shipment ID:</btn>
                        <input id="emptytrailerfilterinputsecondaryid" name="emptytrailerfilterinputsecondaryid" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonsecondaryid" style="border:unset; background-color:rgb(13, 82, 200); color:white; font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdividtrailerwh" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="mb-3 me-2">
                        <btn id="emptytrailerfilterbtnidtrailerwh" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">ID Trailer:</btn>
                        <input id="emptytrailerfilterinputidtrailerwh" name="emptytrailerfilterinputidtrailerwh" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonidtrailerwh" style="border:unset; background-color:rgb(13, 82, 200); color:white; font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfiltersdd" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtnsdd" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Suggested Delivery Date:</btn>
                        <input id="emptytrailerfilterinputstartsdd" name="emptytrailerfilterinputstartsdd" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputendsdd" name="emptytrailerfilterinputendsdd" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonsdd" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivunits" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="mb-3 me-2">
                        <btn id="emptytrailerfilterbtnunits" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Units:</btn>
                        <input id="emptytrailerfilterinputunits" name="emptytrailerfilterinputunits" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonunits" style="border:unset; background-color:rgb(13, 82, 200); color:white; font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivpallets" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="mb-3 me-2">
                        <btn id="emptytrailerfilterbtnpallets" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Pallets:</btn>
                        <input id="emptytrailerfilterinputpallets" name="emptytrailerfilterinputpallets" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonpallets" style="border:unset; background-color:rgb(13, 82, 200); color:white; font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivdriverassigneddate" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtndriverassigneddate" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Driver Assigned Date:</btn>
                        <input id="emptytrailerfilterinputstartdriverassigneddate" name="emptytrailerfilterinputstartdriverassigneddate" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputenddriverassigneddate" name="emptytrailerfilterinputenddriverassigneddate" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttondriverassigneddate" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivpud" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtnpud" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Picked Up Date:</btn>
                        <input id="emptytrailerfilterinputstartpud" name="emptytrailerfilterinputstartpud" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputendpud" name="emptytrailerfilterinputendpud" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonpud" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivitd" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtnitd" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">In Transit Date:</btn>
                        <input id="emptytrailerfilterinputstartitd" name="emptytrailerfilterinputstartitd" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputenditd" name="emptytrailerfilterinputenditd" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonitd" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivdrd" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtndrd" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Delivered/Received Date:</btn>
                        <input id="emptytrailerfilterinputstartdrd" name="emptytrailerfilterinputstartdrd" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputenddrd" name="emptytrailerfilterinputenddrd" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttondrd" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivsyd" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtnsyd" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Secured Yard Date:</btn>
                        <input id="emptytrailerfilterinputstartsyd" name="emptytrailerfilterinputstartsyd" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputendsyd" name="emptytrailerfilterinputendsyd" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonsyd" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivaed" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtnaed" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Approved ETA Date:</btn>
                        <input id="emptytrailerfilterinputstartaed" name="emptytrailerfilterinputstartaed" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputendaed" name="emptytrailerfilterinputendaed" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonaed" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivolt" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtnolt" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Offload Time:</btn>
                        <input id="emptytrailerfilterinputstartolt" name="emptytrailerfilterinputstartolt" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputendolt" name="emptytrailerfilterinputendolt" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonolt" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivdob" style="display: none;">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap; align-items:center" class="input-group mb-3 me-2">
                        <btn id="emptytrailerfilterbtndob" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Date of Billing:</btn>
                        <input id="emptytrailerfilterinputstartdob" name="emptytrailerfilterinputstartdob" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <p style="text-align:center; border:unset;  color:white; background-color:rgb(13, 82, 200); font-size: small; margin:0;">-</p>
                        <input id="emptytrailerfilterinputenddob" name="emptytrailerfilterinputenddob" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small; text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttondob" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivbillingid" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="mb-3 me-2">
                        <btn id="emptytrailerfilterbtnbillingid" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Billing ID:</btn>
                        <input id="emptytrailerfilterinputbillingid" name="emptytrailerfilterinputbillingid" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttonbillingid" style="border:unset; background-color:rgb(13, 82, 200); color:white; font-size: small;" class="btn">X</button>
                    </div>
                </div>

                <div class="col-auto" id="emptytrailerfilterdivdevicenumber" style="display:none">
                    <div style="background-color:rgb(13, 82, 200); border-radius:0.5rem; width:fit-content; display:flex; flex-wrap:nowrap" class="mb-3 me-2">
                        <btn id="emptytrailerfilterbtndevicenumber" style="background-color: unset; color:white; white-space:nowrap; align-content:center; font-size: small;" class="ms-2 me-2">Device Number:</btn>
                        <input id="emptytrailerfilterinputdevicenumber" name="emptytrailerfilterinputdevicenumber" value="" style="border:unset; width:fit-content;  color:white; background-color:rgb(13, 82, 200); font-size: small;text-align:center" type="text" class="">
                        <button id="emptytrailerfilterbuttondevicenumber" style="border:unset; background-color:rgb(13, 82, 200); color:white; font-size: small;" class="btn">X</button>
                    </div>
                </div>

            </div>

    </div>

        <!--Contenido General Pagina-->
        <div class="container  mb-4" style="margin-top: 290px;">

            <!--Tabla mostrar los Shipments existentes-->
            <div class="table_style">
                <table class="table" id="table_wh_eta_approval_shipments">
                    <thead>
                        <tr>
                            <th scope="col">Shipment Type</th>
                            <th scope="col">STM ID</th>
                            <th scope="col">Suggested Delivery Date</th>
                            <th scope="col">Units</th>
                            <th scope="col">Pallets</th>
                        </tr>
                    </thead>
                    <tbody id="shipmentWHTableBody">
                        @foreach ($shipments as $shipment)
                        <tr id="trailer-{{ $shipment->pk_shipment }}" class="clickable-row" 
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#shipmentwhapptapprovaldetails" 
                            aria-controls="shipmentwhapptapprovaldetails" 
                            data-id="{{ $shipment->pk_shipment }}">
                            <td>{{ $shipment->shipmenttype->gntc_description ?? 'N/A' }}</td>
                            <td>{{ $shipment->stm_id }}</td>
                            <td>{{ $shipment->etd }}</td>
                            <td>{{ $shipment->units }}</td>
                            <td>{{ $shipment->pallets }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

             <!--OffCanvas añadir mas filtros-->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasaddmorefilters" aria-labelledby="offcanvasaddmorefiltersLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasaddmorefiltersLabel">Add More Filters</h5>
                    <button type="button" id="offcanvasaddmorefiltersclosebutton" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    
                    <div style="display:none">
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyshipmenttypefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplyshipmenttypefilter" aria-expanded="false" aria-controls="multiCollapseapplyshipmenttypefilter">Shipment Type</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplyshipmenttypefilter">
                                <select class="form-select" aria-label="Default select example" id="inputapplyshipmenttypefilter" name="inputapplyshipmenttypefilter" data-url="{{ route('shipmenttypes-shipment') }}">
                                    <option value="">Choose a filter</option>
                                </select>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applyshipmenttypefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyshipmenttypefiltercheckbox" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplyshipmenttypefiltercheckbox" aria-expanded="false" aria-controls="multiCollapseapplyshipmenttypefiltercheckbox">Shipment Type</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplyshipmenttypefiltercheckbox" data-url="{{ route('shipmenttypes-shipment') }}">
                                    <div id="ShipmentTypeCheckboxContainer">
                                        <!-- Los checkboxes se generarán aquí dinámicamente -->
                                    </div>
                                    <button class="btn btn-primary mt-2 filterapplycheckbox" type="button" id="applyshipmenttypefiltercheckbox">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyidstmfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplyidstmfilter" aria-expanded="false" aria-controls="multiCollapseapplyidstmfilter">ID STM</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplyidstmfilter">
                                <input type="text" class="form-control" id="inputapplyidstmfilter">
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applyidstmfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplysecondaryshipmentfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplysecondaryshipmentfilter" aria-expanded="false" aria-controls="multiCollapseapplysecondaryshipmentfilter">Secondary Shipment ID</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplysecondaryshipmentfilter">
                                <input type="text" class="form-control" id="inputapplysecondaryshipmentfilter">
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applysecondaryshipmentfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyidtrailerwhfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplyidtrailerwhfilter" aria-expanded="false" aria-controls="multiCollapseapplyidtrailerwhfilter">ID Trailer</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplyidtrailerwhfilter">
                                <input type="text" class="form-control" id="inputapplyidtrailerwhfilter">
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applyidtrailerwhfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplysddfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplysddfilter" aria-expanded="false" aria-controls="multiCollapseapplysddfilter">Suggested Delivery Date</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplysddfilter">
                                <div class="d-flex">
                                <input type="text" class="form-control datetimepicker me-2" value="" id="inputapplysddstfilter" name="inputapplysddstfilter" placeholder="Start Date">
                                <input type="text" class="form-control datetimepicker ms-2" value="" id="inputapplysddenfilter" name="inputapplysddenfilter" placeholder="End Date">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applysddfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyunitsfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplyunitsefilter" aria-expanded="false" aria-controls="multiCollapseapplyunitsefilter">Units</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplyunitsefilter">
                                <input type="text" class="form-control" id="inputunitsfilter">
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applyunitsfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplypalletsfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplypalletsfilter" aria-expanded="false" aria-controls="multiCollapseapplypalletsfilter">Pallets</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplypalletsfilter">
                                <input type="text" class="form-control" id="inputpalletsfilter">
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applypalletsfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydadfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplydadfilter" aria-expanded="false" aria-controls="multiCollapseapplydadfilter">Driver Assigned Date</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplydadfilter">
                                <div class="d-flex">
                                <input type="text" class="form-control datetimepicker me-2" value="" id="inputapplydadstfilter" name="inputapplydadstfilter" placeholder="Start Date">
                                <input type="text" class="form-control datetimepicker ms-2" value="" id="inputapplydadenfilter" name="inputapplydadenfilter" placeholder="End Date">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applydadfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplypudfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplypudfilter" aria-expanded="false" aria-controls="multiCollapseapplypudfilter">Picked Up Date</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplypudfilter">
                                <div class="d-flex">
                                <input type="text" class="form-control datetimepicker me-2" value="" id="inputapplypudstfilter" name="inputapplypudstfilter" placeholder="Start Date">
                                <input type="text" class="form-control datetimepicker ms-2" value="" id="inputapplypudenfilter" name="inputapplypudenfilter" placeholder="End Date">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applypudfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyitdfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplyitdfilter" aria-expanded="false" aria-controls="multiCollapseapplyitdfilter">In Transit Date</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplyitdfilter">
                                <div class="d-flex">
                                <input type="text" class="form-control datetimepicker me-2" value="" id="inputapplyitdstfilter" name="inputapplyitdstfilter" placeholder="Start Date">
                                <input type="text" class="form-control datetimepicker ms-2" value="" id="inputapplyitdenfilter" name="inputapplyitdenfilter" placeholder="End Date">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applyitdfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydrdfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplydrdfilter" aria-expanded="false" aria-controls="multiCollapseapplydrdfilter">Delivered/Received Date</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplydrdfilter">
                                <div class="d-flex">
                                <input type="text" class="form-control datetimepicker me-2" value="" id="inputapplydrdstfilter" name="inputapplydrdstfilter" placeholder="Start Date">
                                <input type="text" class="form-control datetimepicker ms-2" value="" id="inputapplydrdenfilter" name="inputapplydrdenfilter" placeholder="End Date">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applydrdfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplysydfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplysydfilter" aria-expanded="false" aria-controls="multiCollapseapplysydfilter">Secured Yard Date</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplysydfilter">
                                <div class="d-flex">
                                <input type="text" class="form-control datetimepicker me-2" value="" id="inputapplysydstfilter" name="inputapplysydstfilter" placeholder="Start Date">
                                <input type="text" class="form-control datetimepicker ms-2" value="" id="inputapplysydenfilter" name="inputapplysydenfilter" placeholder="End Date">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applysydfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyaedfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplyaedfilter" aria-expanded="false" aria-controls="multiCollapseapplyaedfilter">Approved ETA Date</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplyaedfilter">
                                <div class="d-flex">
                                <input type="text" class="form-control datetimepicker me-2" value="" id="inputapplyaedstfilter" name="inputapplyaedstfilter" placeholder="Start Date">
                                <input type="text" class="form-control datetimepicker ms-2" value="" id="inputapplyaedenfilter" name="inputapplyaedenfilter" placeholder="End Date">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applyaedfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyoltfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplyoltfilter" aria-expanded="false" aria-controls="multiCollapseapplyoltfilter">Offload Time</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplyoltfilter">
                                <div class="d-flex">
                                <input type="time" step="1" class="form-control me-2" value="" id="inputapplyoltstfilter" name="inputapplyoltstfilter" placeholder="">
                                <input type="time" step="1" class="form-control ms-2" value="" id="inputapplyoltenfilter" name="inputapplyoltenfilter" placeholder="">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applyoltfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydobfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplydobfilter" aria-expanded="false" aria-controls="multiCollapseapplydobfilter">Date Of Billing</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplydobfilter">
                                <div class="d-flex">
                                <input type="text" class="form-control datetimepicker me-2" value="" id="inputapplydobstfilter" name="inputapplydobstfilter" placeholder="Start Date">
                                <input type="text" class="form-control datetimepicker ms-2" value="" id="inputapplydobenfilter" name="inputapplydobenfilter" placeholder="End Date">
                                </div>
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applydobfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplybillingidfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplybillingidfilter" aria-expanded="false" aria-controls="multiCollapseapplybillingidfilter">Billing ID</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplybillingidfilter">
                                <input type="text" class="form-control" id="inputbillingidfilter">
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applybillingidfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydevicenumberfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseapplydevicenumberfilter" aria-expanded="false" aria-controls="multiCollapseapplydevicenumberfilter">Device Number</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseapplydevicenumberfilter">
                                <input type="text" class="form-control" id="inputdevicenumberfilter">
                                <button class="btn btn-primary mt-2 filterapply" type="button" id="applydevicenumberfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Offcanvas con detalles del Shipment-->
            <div>
                <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="shipmentwhapptapprovaldetails" aria-labelledby="shipmentwhapptapprovaldetailstitle">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="shipmentwhapptapprovaldetailstitle">WH ETA Approval </h5>
                        <button type="button" id="closeoffcanvaswhetaapprovaldetails" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                        <div class=" d-flex justify-content-end m-4">
                            <!--<button type="button" style="color: white;" class="btn btn-success me-2" id="createshipmentwithemptytrailer" data-url="{{ route('createworkflowstartwithemptytrailer') }}"><i class="fa-solid fa-truck"></i></button>
                            <button type="button" style="color: white;" class="btn btn-danger me-2" id="deleteemptytrailercanvas" data-url="{{ url('trailers') }}"><i class="fa-solid fa-trash"></i></button>-->
                            <button type="button" style="color: white;" class="btn btn-success" id="whetaapprovalbutton">WH ETA Approval <i class="fa-solid fa-thumbs-up"></i></button>
                        </div>
                    <div class="offcanvas-body">
                        <p id="offcanvasdetails-pk_shipment" style="display:none;"></p>
                        <!--<p id="pk_availability" style="display:none;"></p>
                        <p id="pk_location" style="display:none;"></p>
                        <p id="pk_carrier" style="display:none;"></p>-->
                        <p><strong>ID Trailer:</strong> <span id="offcanvasdetails-id_trailer"></span></p>
                        <p><strong>ID STM:</strong> <span id="offcanvasdetails-stm_id"></span></p>
                        <p><strong>Suggested Delivery Date:</strong> <span id="offcanvasdetails-etd"></span></p>
                        <p><strong>Units:</strong> <span id="offcanvasdetails-units"></span></p>
                        <p><strong>Pallets:</strong> <span id="offcanvasdetails-pallets"></span></p>
                        <p><strong>Driver Assigned Date:</strong> <span id="offcanvasdetails-driver_assigned_date"></span></p>
                        <p><strong>In Transit Date:</strong> <span id="offcanvasdetails-intransit_date"></span></p>
                        <p><strong>Shipment Type:</strong> <span id="offcanvasdetails-gnct_id_shipment_type"></span></p>
                        <p><strong>Picked Up Date:</strong> <span id="offcanvasdetails-pick_up_date"></span></p>
                    </div>
                </div>
            </div>

            <!--OffCanvas para WH ETA Approval-->
            <div>
                <div class="offcanvas offcanvas-end offcanvas-size" data-bs-scroll="true" tabindex="-1" id="whetaapprovaloffcanvas" aria-labelledby="whetaapprovaloffcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="whetaapprovaloffcanvasWithBothOptionsLabel">WH ETA Approval </h5>
                        <button type="button" class="btn-close" id="closewhetaapprovalbutton" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div>
                            <form id="whetaapprovalformm">
                                @csrf
                                @method('PUT')
                                            <input type="hidden" id="whetainputpkshipment">
                                            <div class="mb-3">
                                                <label for="whaetainputidtrailer" class="form-label">ID Trailer</label>
                                                <input type="text" class="form-control" disabled id="whaetainputidtrailer" name="whaetainputidtrailer" value="{{ old('whaetainputidtrailer') }}">
                                                <div class="invalid-feedback" id="error-whaetainputidtrailer"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="whetainputidstm" class="form-label ">ID STM</label>
                                                <input type="text" class="form-control" disabled id="whetainputidstm" name="whetainputidstm" readonly value="{{ old('whetainputidstm') }}">
                                                <div class="invalid-feedback" id="error-whetainputidstm"></div>
                                            </div>   

                                            <div class="mb-3 ">
                                                <label for="whetainputpallets" class="form-label ">Pallets</label>
                                                <input type="text" class="form-control" id="whetainputpallets" name="whetainputpallets" value="{{ old('whetainputpallets') }}">
                                                <div class="invalid-feedback" id="error-whetainputpallets"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="whetainputunits" class="form-label ">Units</label>
                                                <input type="text" class="form-control" id="whetainputunits" name="whetainputunits" value="{{ old('whetainputunits') }}">
                                                <div class="invalid-feedback" id="error-whetainputunits"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="whetainputedt" class="form-label ">Suggested Delivery Date</label>
                                                <input type="text" disabled class="form-control datetimepicker" id="whetainputedt" name="whetainputedt" value="{{ old('whetainputedt') }}" placeholder="MM/DD/YYYY - H/M/S">
                                                <div class="invalid-feedback" id="error-whetainputedt"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="whetainputapprovedeta" class="form-label ">Approved ETA Datetime</label>
                                                <input type="text" class="form-control datetimepicker" id="whetainputapprovedeta" name="whetainputapprovedeta" value="{{ old('updateinputdateout') }}" placeholder="MM/DD/YYYY - H/M/S">
                                                <div class="invalid-feedback" id="error-whetainputapprovedeta"></div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="whetainputapproveddoornumber" class="form-label ">Door Number</label>
                                                <select class="form-control searchdoornumberwheta" aria-label="Default select example"  id="whetainputapproveddoornumber" name="whetainputapproveddoornumber" value="{{ old('whetainputapproveddoornumber') }}" data-url="{{ route('doornumberwheta-whetaapproval') }}">
                                                    <option selected disabled hidden></option>
                                                </select>
                                                <div class="invalid-feedback" id="error-whetainputapproveddoornumber"></div>
                                            </div>

                                            <!--<div class="mb-3 ">
                                                <label for="whetainputapproveddoornumber" class="form-label ">Door Number</label>
                                                <input type="text" class="form-control" id="whetainputapproveddoornumber" name="whetainputapproveddoornumber" value="" placeholder="">
                                                <div class="invalid-feedback" id="error-whetainputapprovedeta"></div>
                                            </div>-->
     
                                <button type="button" class="btn btn-primary" id="whetaapprovalbuttonsave" data-url="{{ route('shipment.whetaapproval') }}">Update</button>
                            </form>   
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection

@section('scripts')
<!-- Referencia al archivo JS de manera directa -->
<script src="{{ asset('js/whapptapproval.js') }}"></script> <!-- Asegúrate que el archivo esté en public/js -->
@endsection
