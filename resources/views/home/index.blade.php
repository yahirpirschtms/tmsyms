@extends('layouts.app-master')

@section('title', 'Empty Trailer')

@section('content')
    @auth

    <script>//almacenar trailers
        let trailersData = @json($emptyTrailers->keyBy('pk_trailer'));
        console.log(trailersData);
    </script>
    
        <div class="container  my-4">
            <div class="my-4 d-flex justify-content-center align-items-center">
                <h2 class="gradient-text text-capitalize fw-bolder" style="">Empty Trailer</h2>
            </div>

            <!--Botones Añadir y refresh-->
            <div class="d-flex justify-content-end my-4">
            <input class="form-control me-2" type="search" placeholder="Search" id="searchemptytrailer" aria-label="Search">
                <button type="button" class="btn btn-primary me-2" id="refreshemptytrailertable" data-url="{{ route('emptytrailer.data') }}">
                    Refresh
                </button>
                <button class="btn btn-primary" id="addnewemptytrailer" type="button" data-bs-toggle="offcanvas" data-bs-target="#newtrailerempty" aria-controls="offcanvasWithBothOptions">
                    Add
                </button>
            </div>

            <!--Alerta de añadido exitoso-->
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: "{{ session('success') }}",
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
            @endif

            <!--Tabla mostrar los emptytrailers existentes-->
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
                        <tr id="trailer-{{ $trailer->pk_trailer }}" class="clickable-row" 
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#emptytrailer" 
                            aria-controls="emptytrailer" 
                            data-id="{{ $trailer->pk_trailer }}">
                            <td>{{ $trailer->trailer_num }}</td>
                            <td>{{ $trailer->status }}</td>
                            <td>{{ $trailer->pallets_on_trailer }}</td>
                            <td>{{ $trailer->pallets_on_floor }}</td>
                            <td>{{ $trailer->carrier }}</td>
                            <td>{{ $trailer->availabilityIndicator->gntc_description ?? 'N/A' }}</td>
                            <td>{{ $trailer->locations->CoName ?? 'N/A' }}</td>
                            <td>{{ $trailer->date_in }}</td>
                            <td>{{ $trailer->date_out }}</td>
                            <td>{{ $trailer->transaction_date }}</td>
                            <td>{{ $trailer->username }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!--Offcanvas con detalles del trailer-->
            <div>
                <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="emptytrailer" aria-labelledby="offcanvasWithBothOptionsLabel2">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel2">Trailer Details</h5>
                        <button type="button" id="closeoffcanvastrailersdetails" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                        <div class=" d-flex justify-content-end m-4">
                            <button type="button" class="btn btn-success me-2" id="createshipmentwithemptytrailer" data-url="{{ route('createworkflowstartwithemptytrailer') }}">Shipment</button>
                            <button type="button" class="btn btn-danger me-2" id="deleteemptytrailercanvas" data-url="{{ url('trailers') }}">Delete</button>
                            <button type="button" class="btn btn-primary" id="updateemptytrailer">Update</button>
                        </div>
                    <div class="offcanvas-body">
                        <p id="pk_trailer" style="display:none;"></p>
                        <p><strong>ID Trailer:</strong> <span id="offcanvas-id"></span></p>
                        <p><strong>Status:</strong> <span id="offcanvas-status"></span></p>
                        <p><strong>Pallets on Trailer:</strong> <span id="offcanvas-pallets-on-trailer"></span></p>
                        <p><strong>Pallets on Floor:</strong> <span id="offcanvas-pallets-on-floor"></span></p>
                        <p><strong>Carrier:</strong> <span id="offcanvas-carrier"></span></p>
                        <p><strong>Availability:</strong> <span id="offcanvas-availability"></span></p>
                        <p><strong>Location:</strong> <span id="offcanvas-location"></span></p>
                        <p><strong>Date In:</strong> <span id="offcanvas-date-in"></span></p>
                        <p><strong>Date Out:</strong> <span id="offcanvas-date-out"></span></p>
                        <p><strong>Transaction Date:</strong> <span id="offcanvas-transaction-date"></span></p>
                        <p><strong>Username:</strong> <span id="offcanvas-username"></span></p>
                    </div>
                </div>
            </div>

            <!--OffCanvas para registrar nuevo Trailer-->
            <div>
                <div class="offcanvas offcanvas-end offcanvas-size" data-bs-scroll="true" tabindex="-1" id="newtrailerempty" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">New Trailer Empty</h5>
                        <button type="button" id="closenewtrailerregister" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div>
                            <form id="emptytrailerformm">
                                @csrf
                                            <div class="d-flex justify-content-end">
                                                @error('inputidtrailer')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="inputidtrailer" class="form-label">ID Trailer</label>
                                                <input type="text" class="form-control" id="inputidtrailer" name="inputidtrailer" >
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputdateofstatus')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputdateofstatus" class="form-label ">Date Of Status</label>
                                                <input type="text" class="form-control datetms" id="inputdateofstatus" name="inputdateofstatus" placeholder="MM/DD/YYYY" >
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputpalletsontrailer')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputpalletsontrailer" class="form-label ">Pallets On Trailer</label>
                                                <input type="text" class="form-control" id="inputpalletsontrailer" name="inputpalletsontrailer" value="{{ old('inputpalletsontrailer') }}">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputpalletsonfloor')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputpalletsonfloor" class="form-label ">Pallets On Floor</label>
                                                <input type="text" class="form-control" id="inputpalletsonfloor" name="inputpalletsonfloor" value="{{ old('inputpalletsonfloor') }}">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputcarrier')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputcarrier" class="form-label ">Carrier</label>
                                                <select class="form-select searchcarrier" aria-label="Default select example"  id="inputcarrier" name="inputcarrier" value="{{ old('inputcarrier') }}" data-url="{{ route('carrier-emptytrailer') }}">
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputavailabilityindicator')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputavailabilityindicator" class="form-label ">Availability Indicator</label>
                                                <select class="form-select searchavailabilityindicator" aria-label="Default select example"  id="inputavailabilityindicator" name="inputavailabilityindicator" value="{{ old('inputavailabilityindicator') }}" data-url="{{ route('availabilityindicators-emptytrailer') }}">
                                                    <option selected disabled hidden></option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputlocation')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputlocation" class="form-label ">Location</label>
                                                <select class="form-select searchlocation" aria-label="Default select example"  id="inputlocation" name="inputlocation" value="{{ old('inputlocation') }}" data-url="{{ route('locations-emptytrailer') }}">
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputdatein')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputdatein" class="form-label ">Date In</label>
                                                <input type="text" class="form-control datetimepicker" id="inputdatein" name="inputdatein" placeholder="MM/DD/YYYY - H/M/S">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputdateout')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputdateout" class="form-label ">Date Out</label>
                                                <input type="text" class="form-control datetimepicker" id="inputdateout" name="inputdateout" placeholder="MM/DD/YYYY - H/M/S">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputtransactiondate')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputtransactiondate" class="form-label ">Transaction Date</label>
                                                <input type="text" class="form-control datetimepicker" id="inputtransactiondate" name="inputtransactiondate" placeholder="MM/DD/YYYY - H/M/S">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                @error('inputusername')
                                                    <h6 class="alert alert-danger">{{  $message  }}</h6>
                                                @enderror
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="inputusername" class="form-label ">Username</label>
                                                <input type="text" class="form-control" id="inputusername" name="inputusername" value="{{ old('inputusername') }}">
                                                <div class="invalid-feedback"></div>
                                            </div>        
                                <button type="submit" class="btn btn-primary" id="saveButton" data-url="{{ route('emptytrailer.store') }}">Save</button >
                            </form>   
                        </div>
                    </div>
                </div>
            </div>

            <!--OffCanvas para Actualizar un Trailer-->
            <div>
                <div class="offcanvas offcanvas-end offcanvas-size" data-bs-scroll="true" tabindex="-1" id="updatenewtrailerempty" aria-labelledby="updateoffcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="updateoffcanvasWithBothOptionsLabel">Update Trailer Empty</h5>
                        <button type="button" class="btn-close" id="closeupdatemptytrailerbutton" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div>
                            <form id="updateemptytrailerformm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="updateinputpktrailer">
                                            <div class="mb-3">
                                                <label for="updateinputidtrailer" class="form-label">ID Trailer</label>
                                                <input type="text" class="form-control" id="updateinputidtrailer" name="updateinputidtrailer" value="{{ old('updateinputidtrailer') }}">
                                                <div class="invalid-feedback" id="error-updateinputidtrailer"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputdateofstatus" class="form-label ">Date Of Status</label>
                                                <input type="text" class="form-control datetms" id="updateinputdateofstatus" name="updateinputdateofstatus" >
                                                <div class="invalid-feedback" id="error-updateinputdateofstatus"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputpalletsontrailer" class="form-label ">Pallets On Trailer</label>
                                                <input type="text" class="form-control" id="updateinputpalletsontrailer" name="updateinputpalletsontrailer" value="{{ old('updateinputpalletsontrailer') }}">
                                                <div class="invalid-feedback" id="error-updateinputpalletsontrailer"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputpalletsonfloor" class="form-label ">Pallets On Floor</label>
                                                <input type="text" class="form-control" id="updateinputpalletsonfloor" name="updateinputpalletsonfloor" value="{{ old('updateinputpalletsonfloor') }}">
                                                <div class="invalid-feedback" id="error-updateinputpalletsonfloor"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputcarrier" class="form-label ">Carrier</label>
                                                <select class="form-select searchcarrier" aria-label="Default select example"  id="updateinputcarrier" name="updateinputcarrier" value="{{ old('updateinputcarrier') }}" data-url="{{ route('carrier-emptytrailer') }}">
                                                </select>
                                                <div class="invalid-feedback" id="error-updateinputcarrier"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputavailabilityindicator" class="form-label ">Availability Indicator</label>
                                                <select class="form-select searchavailabilityindicator" aria-label="Default select example"  id="updateinputavailabilityindicator" name="updateinputavailabilityindicator" value="{{ old('updateinputavailabilityindicator') }}" data-url="{{ route('availabilityindicators-emptytrailer') }}">
                                                    <option selected disabled hidden></option>
                                                </select>
                                                <div class="invalid-feedback" id="error-updateinputavailabilityindicator"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputlocation" class="form-label ">Location</label>
                                                <select class="form-select searchlocation" aria-label="Default select example"  id="updateinputlocation" name="updateinputlocation" value="{{ old('updateinputlocation') }}" data-url="{{ route('locations-emptytrailer') }}">
                                                </select>
                                                <div class="invalid-feedback" id="error-updateinputlocation"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputdatein" class="form-label ">Date In</label>
                                                <input type="text" class="form-control datetimepicker" id="updateinputdatein" name="updateinputdatein" value="{{ old('updateinputdatein') }}">
                                                <div class="invalid-feedback" id="error-updateinputdatein"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputdateout" class="form-label ">Date Out</label>
                                                <input type="text" class="form-control datetimepicker" id="updateinputdateout" name="updateinputdateout" value="{{ old('updateinputdateout') }}">
                                                <div class="invalid-feedback" id="error-updateinputdateout"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputtransactiondate" class="form-label ">Transaction Date</label>
                                                <input type="text" class="form-control datetimepicker" id="updateinputtransactiondate" name="updateinputtransactiondate" value="{{ old('updateinputtransactiondate') }}">
                                                <div class="invalid-feedback" id="error-updateinputtransactiondate"></div>
                                            </div>

                                            <div class="mb-3 ">
                                                <label for="updateinputusername" class="form-label ">Username</label>
                                                <input type="text" class="form-control" id="updateinputusername" name="updateinputusername" value="{{ old('updateinputusername') }}">
                                                <div class="invalid-feedback" id="error-updateinputusername"></div>
                                            </div>        
                                <button type="button" class="btn btn-primary" id="updatesaveButton" data-url="{{ route('emptytrailer.update') }}">Update</button>
                            </form>   
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
    <script src="{{ asset('js/emptytrailer.js') }}"></script> <!-- Asegúrate que el archivo esté en public/js -->
@endsection



