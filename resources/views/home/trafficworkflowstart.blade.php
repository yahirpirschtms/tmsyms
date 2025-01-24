@extends('layouts.app-master')

@section('title', 'Traffic Workflow Start')

@section('content')
    @auth
        <div class="container  my-4">
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" >Traffic Workflow Start</h2>
            </div>
            <form id="createnewshipmentform"  class="centered-form">
            @csrf
                <div class="mb-3">
                    <label for="inputshipmentstmid" class="form-label ">STM ID</label>
                    <input type="text" class="form-control" id="inputshipmentstmid" name="inputshipmentstmid">
                    <!--<select class="form-select" aria-label="Default select example"  id="inputshipmentstmid" name="inputshipmentstmid" data-url="{{ route('services-shipment') }}">
                    </select>*-->
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentshipmenttype" class="form-label ">Shipment Type</label>
                    <select class="form-select" aria-label="Default select example"  id="inputshipmentshipmenttype" name="inputshipmentshipmenttype" data-url="{{ route('shipmenttypes-shipment') }}">
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3" style="display: none;">
                    <label for="inputshipmentcurrentstatus" class="form-label ">Current Status</label>
                    <select class="form-select" aria-label="Default select example"  id="inputshipmentcurrentstatus" name="inputshipmentcurrentstatus" data-url="{{ route('currentstatus-shipment') }}">
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentreference" class="form-label ">Reference</label>
                    <input type="text" class="form-control" id="inputshipmentreference" name="inputshipmentreference">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="inputshipmentcheckbonded" name="inputshipmentcheckbonded">
                    <label class="form-check-label" for="inputshipmentcheckbonded">
                        Bonded
                    </label>
                </div>
                
                <div class="mb-3">
                    <label for="inputorigin" class="form-label ">Origin</label>
                    <select class="form-select" aria-label="Default select example"  id="inputorigin" name="inputorigin" value="{{ old('inputorigin', $from_button == 1 ? $location : '') }}" data-url="{{ route('locations-emptytrailer') }}">
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentdestination" class="form-label ">Destination</label>
                    <select class="form-select" aria-label="Default select example"  id="inputshipmentdestination" name="inputshipmentdestination" data-url="{{ route('destinations-shipments') }}">
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentprealertdatetime" class="form-label ">PreAlert DateTime</label>
                    <input type="text" class="form-control datetimepicker" id="inputshipmentprealertdatetime" name="inputshipmentprealertdatetime" placeholder="MM/DD/YYYY - H/M/S">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputidtrailer" class="form-label ">Trailer ID</label>
                    <input type="text" class="form-control" id="inputidtrailer" name="inputidtrailer" disabled value="{{ old('inputidtrailer', $from_button == 1 ? $trailerId : '') }}">
                    <div class="invalid-feedback"></div>
                    <input type="hidden" name="inputidtrailer" value="{{ old('inputidtrailer', $from_button == 1 ? $trailerId : '') }}">
                </div>

                <div class="mb-3">
                    <label for="inputshipmentcarrier" class="form-label ">Carrier Dropping Trailer</label>
                    <select class="form-select" aria-label="Default select example" disabled  id="inputshipmentcarrier" name="inputshipmentcarrier" value="{{ old('inputshipmentcarrier', $from_button == 1 ? $carrier : '') }}" data-url="{{ route('locations-emptytrailer') }}">
                    <option value="">Choose an option</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <input type="hidden" name="inputshipmentcarrier" value="{{ old('inputshipmentcarrier', $from_button == 1 ? $carrier : '') }}">
                </div>

                <div class="mb-3">
                    <label for="inputshipmentdriver" class="form-label ">Driver</label>
                    <select class="form-select" aria-label="Default select example"  id="inputshipmentdriver" name="inputshipmentdriver" data-url="{{ url('drivers-shipments') }}">
                    </select>
                </div>

                <div class="mb-3">
                    <label for="inputshipmenttrailer" class="form-label ">Trailer Owner</label>
                    <select class="form-select" aria-label="Default select example" disabled id="inputshipmenttrailer" name="inputshipmenttrailer" value="{{ old('inputshipmenttrailer', $from_button == 1 ? $carrier : '') }}" data-url="{{ route('locations-emptytrailer') }}">
                    </select>
                    <div class="invalid-feedback"></div>
                    <input type="hidden" name="inputshipmenttrailer" value="{{ old('inputshipmenttrailer', $from_button == 1 ? $carrier : '') }}">
                </div>

                <div class="mb-3">
                    <label for="inputshipmenttruck" class="form-label ">Truck</label>
                    <!--<select class="form-select" aria-label="Default select example"  id="inputshipmenttruck" name="inputshipmenttruck">
                    </select>-->
                    <input type="text" class="form-control" id="inputshipmenttruck" name="inputshipmenttruck">
                    <div class="invalid-feedback"></div>
               </div>

                <div class="mb-3">
                    <label for="inputshipmentetd" class="form-label ">Estimated date of departure</label>
                    <input type="text" class="form-control datetimepicker" id="inputshipmentetd" name="inputshipmentetd" placeholder="MM/DD/YYYY - H/M/S">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentsunits" class="form-label ">Units</label>
                    <input type="text" class="form-control" id="inputshipmentsunits" name="inputshipmentsunits">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputpallets" class="form-label ">Pallets</label>
                    <input type="text" class="form-control" id="inputpallets" name="inputpallets" value="{{ old('inputpallets', $from_button == 1 ? $palletsontrailer : '') }}">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentsecurityseals" class="form-label ">Security Seals</label>
                    <input type="text" class="form-control" id="inputshipmentsecurityseals" name="inputshipmentsecurityseals">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentdevicenumber" class="form-label ">Device Number</label>
                    <input type="text" class="form-control" id="inputshipmentdevicenumber" name="inputshipmentdevicenumber">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentnotes" class="form-label ">Notes</label>
                    <input type="text" class="form-control" id="inputshipmentnotes" name="inputshipmentnotes">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentoverhaulid" class="form-label ">Overhaul ID</label>
                    <input type="text" class="form-control" id="inputshipmentoverhaulid" name="inputshipmentoverhaulid">
                    <div class="invalid-feedback"></div>
                </div>


            <button type="submit" class="btn btn-primary" id="saveButtonShipment" data-url="{{ route('shipment.store') }}">Save</button>

            <!--<a href="{{ route('all-shipments') }}" class="btn btn-primary">All Shipments</a>-->

            </form>
        </div>

       

    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection


@section('scripts')
    <!-- Referencia al archivo JS de manera directa -->
    <script src="{{ asset('js/trafficworkflowstart.js') }}"></script> <!-- Asegúrate que el archivo esté en public/js -->
@endsection
