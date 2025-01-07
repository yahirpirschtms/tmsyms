@extends('layouts.app-master')

@section('title', 'Empty Trailer')

@section('content')
    @auth
        <div class="container  my-4">
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" style="">Empty Trailer</h2>
            </div>
            <div class="d-flex justify-content-end my-4">
                <button type="button" class="btn btn-primary me-2" id="refreshemptytrailertable">
                    Refresh
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#emptytrailermodal">
                    Add
                </button>
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
                    <tbody id="emptyTrailerTableBody">
                        @foreach ($emptyTrailers as $trailer)
                        <tr id="trailer-{{ $trailer->pk_trailer }}">
                            <td>{{ $trailer->trailer_num }}</td>
                            <td>{{ $trailer->status }}</td>
                            <td>{{ $trailer->pallets_on_trailer }}</td>
                            <td>{{ $trailer->pallets_on_floor }}</td>
                            <td>{{ $trailer->carrier }}</td>
                            <td>{{ $trailer->gnct_id_avaibility_indicator }}</td>
                            <td>{{ $trailer->location }}</td>
                            <td>{{ $trailer->date_in }}</td>
                            <td>{{ $trailer->date_out }}</td>
                            <td>{{ $trailer->transaction_date }}</td>
                            <td>{{ $trailer->username }}</td>
                            <td><button type="button" class="btn btn-danger delete-btn" data-id="{{ $trailer->pk_trailer }}">Delete</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div>
                <form id="emptytrailerformm" method="POST" action="{{ route('emptytrailer.store') }}">
                    @csrf
                                <div class="d-flex justify-content-end">
                                    @error('inputidtrailer')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row">
                                    <label for="inputidtrailer" class="col-sm-2 col-form-label">ID Trailer</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputidtrailer" name="inputidtrailer" value="{{ old('inputidtrailer') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputdateofstatus')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputdateofstatus" class="col-sm-2 col-form-label ">Date Of Status</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="inputdateofstatus" name="inputdateofstatus" value="{{ old('inputdateofstatus') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputpalletsontrailer')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputpalletsontrailer" class="col-sm-2 col-form-label ">Pallets On Trailer</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputpalletsontrailer" name="inputpalletsontrailer" value="{{ old('inputpalletsontrailer') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputpalletsonfloor')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputpalletsonfloor" class="col-sm-2 col-form-label ">Pallets On Floor</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputpalletsonfloor" name="inputpalletsonfloor" value="{{ old('inputpalletsonfloor') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputcarrier')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputcarrier" class="col-sm-2 col-form-label ">Carrier</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" aria-label="Default select example"  id="inputcarrier" name="inputcarrier" value="{{ old('inputcarrier') }}">
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputavailabilityindicator')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputavailabilityindicator" class="col-sm-2 col-form-label ">Availability Indicator</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" aria-label="Default select example"  id="inputavailabilityindicator" name="inputavailabilityindicator" value="{{ old('inputavailabilityindicator') }}">
                                            <option selected disabled hidden></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputlocation')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputlocation" class="col-sm-2 col-form-label ">Location</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" aria-label="Default select example"  id="inputlocation" name="inputlocation" value="{{ old('inputlocation') }}">
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputdatein')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputdatein" class="col-sm-2 col-form-label ">Date In</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" class="form-control" id="inputdatein" name="inputdatein" value="{{ old('inputdatein') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputdateout')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputdateout" class="col-sm-2 col-form-label ">Date Out</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" class="form-control" id="inputdateout" name="inputdateout" value="{{ old('inputdateout') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputtransactiondate')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputtransactiondate" class="col-sm-2 col-form-label ">Transaction Date</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" class="form-control" id="inputtransactiondate" name="inputtransactiondate" value="{{ old('inputtransactiondate') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @error('inputusername')
                                        <h6 class="alert alert-danger">{{  $message  }}</h6>
                                    @enderror
                                </div>
                                <div class="mb-3 row ">
                                    <label for="inputusername" class="col-sm-2 col-form-label ">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputusername" name="inputusername" value="{{ old('inputusername') }}">
                                    </div>
                                </div>        
                    <button type="submit" class="btn btn-primary" id="saveButton">Save</button>
                </form>   
            </div>

    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection



