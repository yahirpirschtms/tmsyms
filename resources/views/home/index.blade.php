@extends('layouts.app-master')

@section('title', 'Empty Trailer')

@section('content')
    @auth
        <div class="container  my-4">
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" style="">Empty Trailer</h2>
            </div>
            <div class="d-flex justify-content-end my-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#emptytrailermodal">
                    Add
                </button>
            </div>
            <div>
                <!-- Modal -->
                <div class="modal fade" id="emptytrailermodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emptytrailermodalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl  modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="emptytrailermodalLabel">New Trailer Empty</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                                <label for="inputidtrailer" class="col-sm-2 col-form-label">ID Trailer</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputidtrailer" name="inputidtrailer">
                                </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputdateofstatus" class="col-sm-2 col-form-label ">Date Of Status</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="inputdateofstatus" name="inputdateofstatus">
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputpalletsontrailer" class="col-sm-2 col-form-label ">Pallets On Trailer</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputpalletsontrailer" name="inputpalletsontrailer">
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputpalletsonfloor" class="col-sm-2 col-form-label ">Pallets On Floor</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputpalletsonfloor" name="inputpalletsonfloor">
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputcarrier" class="col-sm-2 col-form-label ">Carrier</label>
                            <div class="col-sm-10">
                                <select class="form-select" aria-label="Default select example"  id="inputcarrier" name="inputcarrier">
                                    <option selected>Open this select menu</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputavailabilityindicator" class="col-sm-2 col-form-label ">Availability Indicator</label>
                            <div class="col-sm-10">
                                <select class="form-select" aria-label="Default select example"  id="inputavailabilityindicator" name="inputavailabilityindicator">
                                    <option selected>Choose an option</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputlocation" class="col-sm-2 col-form-label ">Location</label>
                            <div class="col-sm-10">
                                <select class="form-select" aria-label="Default select example"  id="inputlocation" name="inputlocation">
                                    <option selected>Choose an option</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputdatein" class="col-sm-2 col-form-label ">Date In</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" class="form-control" id="inputdatein" name="inputdatein">
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputdateout" class="col-sm-2 col-form-label ">Date Out</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" class="form-control" id="inputdateout" name="inputdateout">
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputtransactiondate" class="col-sm-2 col-form-label ">Transaction Date</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" class="form-control" id="inputtransactiondate" name="inputtransactiondate">
                            </div>
                        </div>
                        <div class="mb-3 row ">
                            <label for="inputusername" class="col-sm-2 col-form-label ">Pallets On Floor</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputusername" name="inputusername">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID Trailer</th>
                        <th scope="col">Date Of Status</th>
                        <th scope="col">Pallets On Trailer</th>
                        <th scope="col">Pallets On floor</th>
                        <th scope="col">Carrier</th>
                        <th scope="col">Availability Indicator</th>
                        <th scope="col">Location</th>
                        <th scope="col">Date In</th>
                        <th scope="col">Date Out</th>
                        <th scope="col">Transaction Date</th>
                        <th scope="col">Username</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                </table>
            </div>

    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection



