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
                    <input type="text" class="form-control" id="inputshipmentstmid" name="inputshipmentstmid" data-url="{{ route('information-shipment') }}">
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

                <div class="mb-3" style="display:none">
                    <label for="inputshipmentcurrentstatus" class="form-label ">Current Status</label>
                    <select class="form-select" aria-label="Default select example"  id="inputshipmentcurrentstatus" name="inputshipmentcurrentstatus" data-url="{{ route('currentstatus-shipment') }}">
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentreference" class="form-label ">Carrier Reference</label>
                    <input type="text" class="form-control" id="inputshipmentreference" name="inputshipmentreference">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="inputshipmentcheckbonded" name="inputshipmentcheckbonded">
                    <label class="form-check-label" for="inputshipmentcheckbonded">
                        Bonded
                    </label>
                </div>
                
                <!---<div class="mb-3">
                    <label for="inputorigin" class="form-label ">Origin</label>
                    <select class="form-select" aria-label="Default select example"  id="inputorigin" name="inputorigin" data-location="{{ old('inputorigin', $from_button == 1 ? $location : '') }}" data-url="{{ route('locations-emptytrailerAjax') }}">
                    <option selected disabled hidden></option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>-->

                <div class="mb-3">
                    <label for="inputorigin" class="form-label ">Origin</label>
                    <input class="form-control" disabled aria-label="Default select example"  id="inputorigin" name="inputorigin">
                    <div class="invalid-feedback"></div>
                    <input class="form-control" style="display:none"  aria-label="Default select example"  id="inputoriginn" name="inputorigin">
                </div>

                <div class="mb-3" style="display:none">
                    <label for="ln_code" class="form-label ">Lane Code</label>
                    <input class="form-control" aria-label="Default select example"  id="ln_code" name="ln_code">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentdestination" class="form-label ">Destination</label>
                    <input class="form-control " disabled aria-label="Default select example"  id="inputshipmentdestination" name="inputshipmentdestination">
                    <div class="invalid-feedback"></div>
                    <input class="form-control " style="display:none" aria-label="Default select example"  id="inputshipmentdestinationn" name="inputshipmentdestination">
                </div>

                <!--<div class="mb-3">
                    <label for="inputshipmentdestination" class="form-label ">Destination</label>
                    <select class="form-select" aria-label="Default select example"  id="inputshipmentdestination" name="inputshipmentdestination" data-url="{{ route('destinations-shipments') }}">
                    <option selected disabled hidden></option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>-->
                
                <!--<div class="mb-3">
                    <label for="inputshipmentdestination" class="form-label">Destination</label>
                    <input type="text" class="form-control" id="inputshipmentdestination" name="inputshipmentdestination" list="destinations-list" data-url="{{ route('destinations-shipments') }}">
                    <datalist id="destinations-list"></datalist>
                    <div class="invalid-feedback"></div>
                </div>-->

                <div class="mb-3">
                    <label for="inputshipmentprealertdatetime" class="form-label ">PreAlert DateTime</label>
                    <input type="text" class="form-control datetimepicker" id="inputshipmentprealertdatetime" name="inputshipmentprealertdatetime" placeholder="MM/DD/YYYY - H/M/S">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3" style="display:none">
                    <label for="inputpktrailer" class="form-label ">Trailer PK</label>
                    <input type="text" class="form-control" id="inputpktrailer" name="inputpktrailer"  value="{{ ( $from_button == 1 ? $trailerPK : '') }}">
                    <div class="invalid-feedback"></div>
                    <!--<input type="hidden" name="inputidtrailer" value="{{ old('inputidtrailer', $from_button == 1 ? $trailerId : '') }}">-->
                </div>

                <div class="mb-3">
                    <label for="inputidtrailer" class="form-label ">Trailer ID</label>
                    <input type="text" class="form-control" id="inputidtrailer" name="inputidtrailer"  value="{{ ( $from_button == 1 ? $trailerId : '') }}">
                    <div class="invalid-feedback"></div>
                    <!--<input type="hidden" name="inputidtrailer" value="{{ old('inputidtrailer', $from_button == 1 ? $trailerId : '') }}">-->
                </div>

                <div class="mb-3">
                    <label for="inputshipmentcarrier" class="form-label ">Carrier Dropping Trailer</label>
                    <select class="form-select" aria-label="Default select example"   id="inputshipmentcarrier" name="inputshipmentcarrier" data-carrier="{{$from_button == 1 ? $carrier : '' }}" data-url="{{ route('carrier-emptytrailerAjax') }}">
                    <option selected disabled hidden></option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <!--<input type="hidden" name="inputshipmentcarrier" value="{{ old('inputshipmentcarrier', $from_button == 1 ? $carrier : '') }}">-->
                </div>

                <div class="mb-3">
                    <label for="inputshipmentdriver" class="form-label ">Driver</label>
                    <select class="form-select" aria-label="Default select example"  id="inputshipmentdriver" name="inputshipmentdriver" data-url="{{ url('drivers-shipment') }}">
                    <option selected disabled hidden></option>    
                </select>
                </div>

                <div class="mb-3">
                    <label for="inputshipmenttrailer" class="form-label ">Trailer Owner</label>
                    <select class="form-select" aria-label="Default select example"  id="inputshipmenttrailer" name="inputshipmenttrailer" data-trailerowner="{{ $from_button == 1 ? $carrier : ''}}" data-url="{{ route('trailerowner-emptytrailerAjax') }}">
                    <option selected disabled hidden></option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <!--<input type="hidden" name="inputshipmenttrailer" value="{{ old('inputshipmenttrailer', $from_button == 1 ? $carrier : '') }}">-->
                </div>

                <div class="mb-3">
                    <label for="inputshipmenttruck" class="form-label ">Truck</label>
                    <!--<select class="form-select" aria-label="Default select example"  id="inputshipmenttruck" name="inputshipmenttruck">
                    </select>-->
                    <input type="text" class="form-control" id="inputshipmenttruck" name="inputshipmenttruck">
                    <div class="invalid-feedback"></div>
               </div>

                <div class="mb-3">
                    <label for="inputshipmentetd" class="form-label ">Estimated date of delivery (ETD)</label>
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
                    <input type="text" class="form-control" id="inputpallets" name="inputpallets" value="{{ ($from_button == 1 ? $palletsontrailer : '') }}">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentsecurityseals" class="form-label ">Security Seal One</label>
                    <input type="text" class="form-control" id="inputshipmentsecurityseals" name="inputshipmentsecurityseals">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentsecurityseals2" class="form-label ">Security Seal Two</label>
                    <input type="text" class="form-control" id="inputshipmentsecurityseals2" name="inputshipmentsecurityseals2">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3 d-flex">
                    <div class="p-2 pe-0 flex-grow-1">
                        <label class="form-label ">Shipment trackers</label>
                    </div>
                    <div class="p-2">
                        <button type="button" class="btn btn-primary" id="addtrackers">Add Tracker <i class="fa-solid fa-plus"></i></button>
                    </div>
                    <div class="p-2">
                        <button type="button" class="btn btn-danger" id="removetrackers">Remove Tracker <i class="fa-solid fa-minus"></i></button>
                    </div>
                </div>

                <!-- Estos divs se agregarán dinámicamente -->
                <div class="trackers-container"></div>

                <!--<div class="mb-3 tracker-input" id="tracker1" style="display:none">
                    <label for="inputshipmentdevicenumber" class="form-label ">Tracker One</label>
                    <input type="text" class="form-control" id="inputshipmentdevicenumber" name="inputshipmentdevicenumber">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3 tracker-input" id="tracker2" style="display:none">
                    <label for="tracker2" class="form-label ">Tracker Two</label>
                    <input type="text" class="form-control" id="tracker2" name="tracker2">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3 tracker-input" id="tracker3" style="display:none">
                    <label for="tracker3" class="form-label ">Tracker Three</label>
                    <input type="text" class="form-control" id="tracker3" name="tracker3">
                    <div class="invalid-feedback"></div>
                </div>-->

                <div class="mb-3">
                    <label for="inputshipmentnotes" class="form-label ">Notes</label>
                    <input type="text" class="form-control" id="inputshipmentnotes" name="inputshipmentnotes">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentoverhaulid" class="form-label ">Security Company ID</label>
                    <input type="text" class="form-control" id="inputshipmentoverhaulid" name="inputshipmentoverhaulid">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="inputshipmentsecuritycompany" class="form-label ">Security Company</label>
                    <select class="form-select searchsecuritycompany" aria-label="Default select example"  id="inputshipmentsecuritycompany" name="inputshipmentsecuritycompany" data-url="{{ url('securitycompany-shipment') }}">
                    <option selected disabled hidden></option>    
                    </select>
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
