@extends('layouts.app-master')

@section('title', 'All Shipments')

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
                    <input type="text" class="form-control" id="inputstmid">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputshipmenttype" class="col-sm-2 col-form-label ">Shipment Type</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example"  id="inputshipmenttype">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputreference" class="col-sm-2 col-form-label ">Reference</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputreference">
                </div>
            </div>
            <div class="mb-3 row ">
                <label for="inputprealertdatetime" class="col-sm-2 col-form-label ">Reference</label>
                <div class="col-sm-10">
                    <input type="datetime-local" class="form-control" id="inputprealertdatetime">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

            <button type="submit" class="btn btn-primary">All Shipments</button>

            </form>
        </div>

       

    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection
