@extends('layouts.app-master')

@section('title', 'Traffic Workflow Start')

@section('content')
    @auth
        <div class="container  my-4">
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" >Traffic Workflow Start</h2>
            </div>
            <form>
            <div class="mb-3 row ">
                <label for="inputstmid" class="col-sm-2 col-form-label ">STM ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputstmid" name="inputstmid">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputshipmenttype" class="col-sm-2 col-form-label ">Shipment Type</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example"  id="inputshipmenttype" name="inputshipmenttype">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputreference" class="col-sm-2 col-form-label ">Reference</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputreference" name="inputreference">
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="boundedCheckDefault" name="boundedCheckDefault">
                <label class="form-check-label" for="boundedCheckDefault">
                    Bounded
                </label>
            </div>
            <div class="mb-3 row ">
                <label for="inputorigin" class="col-sm-2 col-form-label ">Origin</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example"  id="inputorigin" name="inputorigin">
                        <option selected>Open this select menu</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputdestination" class="col-sm-2 col-form-label ">Destination</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example"  id="inputdestination" name="inputdestination">
                        <option selected>Open this select menu</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputprealertdatetime" class="col-sm-2 col-form-label ">PreAlert DateTime</label>
                <div class="col-sm-10">
                    <input type="datetime-local" class="form-control" id="inputprealertdatetime" name="inputprealertdatetime">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputidtrailer" class="col-sm-2 col-form-label ">Trailer ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputidtrailer" name="inputidtrailer">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputcarrier" class="col-sm-2 col-form-label ">Carrier Dropping Trailer</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example"  id="inputcarrier" name="inputcarrier">
                        <option selected>Open this select menu</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputdriver" class="col-sm-2 col-form-label ">Driver</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example"  id="inputdriver" name="inputdriver">
                        <option selected>Open this select menu</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputtrailer" class="col-sm-2 col-form-label ">Trailer</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example"  id="inputtrailer" name="inputtrailer">
                        <option selected>Open this select menu</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputtruck" class="col-sm-2 col-form-label ">Truck</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example"  id="inputtruck" name="inputtruck">
                        <option selected>Open this select menu</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputetd" class="col-sm-2 col-form-label ">Estimated date of departure</label>
                <div class="col-sm-10">
                    <input type="datetime-local" class="form-control" id="inputetd" name="inputetd">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputunits" class="col-sm-2 col-form-label ">Units</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputunits" name="inputunits">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputpallets" class="col-sm-2 col-form-label ">Pallets</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputpallets" name="inputpallets">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputsecurityseals" class="col-sm-2 col-form-label ">Security Seals</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputsecurityseals" name="inputsecurityseals">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputdevicenumber" class="col-sm-2 col-form-label ">Device Number</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputdevicenumber" name="inputdevicenumber">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputoverhaulid" class="col-sm-2 col-form-label ">Overhaul ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputoverhaulid" name="inputoverhaulid">
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>

            <a href="{{ route('all-shipments') }}" class="btn btn-primary">All Shipments</a>

            </form>
        </div>



    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection

