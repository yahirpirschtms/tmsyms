@extends('layouts.app-master')

@section('title', 'All Shipments')

@section('content')
    @auth
    <div class="container my-4">
        <!-- Título centrado -->
        <div class="d-flex justify-content-center my-4">
            <h2 class="gradient-text text-capitalize fw-bolder">All Shipments</h2>
        </div>
         <!-- Botones Añadir y Refresh -->
         <div class="d-flex justify-content-end mt-4 mb-2">
            <!-- Search Input for All Shipments -->
            <div style="position: relative; display: inline-block; width: 100%;" class="me-4">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #6c757d; cursor: pointer;" onclick="document.getElementById('searchByShipment').focus()"></i>
                <input class="form-control" type="search" placeholder="    Search WH Appoinment Viewer" name="searchByShipment" id="searchByShipment" aria-label="Search" style="padding-left: 30px;">
            </div>

            <!-- Export Button -->
            <button type="button" class="btn me-2 btn-success" id="exportfile" data-bs-toggle="tooltip" data-bs-placement="top" title="Export File">
                <i class="fa-solid fa-file-export"></i>
            </button>

            <!-- Refresh Table Button -->
            <button type="button" class="btn me-2 btn-primary" id="refreshshipmentstable" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh Table">
                <i class="fa-solid fa-arrows-rotate"></i>
            </button>

            <!-- Add More Filters Button -->
            <button class="btn" id="addmorefiltersallshipments" style="color: white;background-color:orange;" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasaddmorefilters" aria-controls="offcanvasaddmorefilters">
                <i class="fa-solid fa-filter"></i>
            </button>
        </div>

            <!--OffCanvas añadir más filtros-->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasaddmorefilters" aria-labelledby="offcanvasaddmorefiltersLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasaddmorefiltersLabel">Add More Filters</h5>
                    <button type="button" id="offcanvasaddmorefiltersclosebutton" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <!-- Filtro por Shipment Type -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyshipmenttypefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsshipmenttypefilter" aria-expanded="false" aria-controls="multiCollapsshipmenttypefilter">
                            Shipment Type
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsshipmenttypefilter">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="filterShipping" value="Shipping">
                                        <label class="form-check-label" for="filterShipping">Shipping</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="filterReceiving" value="Receiving">
                                        <label class="form-check-label" for="filterReceiving">Receiving</label>
                                    </div>
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyshipmenttypefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por STM ID -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplystmfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsstmfilter" aria-expanded="false" aria-controls="multiCollapsstmfilter">STM ID</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsstmfilter">
                                    <input type="text" class="form-control" id="inputapplystmfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applystmfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Secondary Shipment ID -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplysecondaryshipmentidfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapssecondaryshipmentidfilter" aria-expanded="false" aria-controls="multiCollapssecondaryshipmentidfilter">Secondary Shipment ID</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapssecondaryshipmentidfilter">
                                    <input type="text" class="form-control" id="inputapplysecondaryshipmentidfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applysecondaryshipmentidfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Landstar Reference -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplylandstarreferencefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapslandstarreferencefilter" aria-expanded="false" aria-controls="multiCollapslandstarreferencefilter">Landstar Reference</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapslandstarreferencefilter">
                                    <input type="text" class="form-control" id="inputapplylandstarreferencefilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applylandstarreferencefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Origin con checkboxes -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyoriginfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsoriginfilter" aria-expanded="false" aria-controls="multiCollapsoriginfilter">Origin</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsoriginfilter">
                                    <div>
                                        <input type="checkbox" id="originBW2" value="BW2"> BW2<br>
                                        <input type="checkbox" id="originBW3" value="BW3"> BW3<br>
                                        <input type="checkbox" id="origin3PA" value="3PA"> 3PA<br>
                                        <input type="checkbox" id="originELP" value="ELP"> ELP<br>
                                        <input type="checkbox" id="originSeaboardMarine" value="Seaboard Marine"> Seaboard Marine<br>
                                        <input type="checkbox" id="originFSC" value="FSC Lebanon"> FSC Lebanon<br>
                                        <input type="checkbox" id="originOnTimeForwarding" value="On Time Forwarding"> On Time Forwarding<br>
                                        <input type="checkbox" id="originTFEMAYard" value="TFEMA Yard"> TFEMA Yard<br>
                                        <input type="checkbox" id="originFoxconn" value="Foxconn"> Foxconn<br>
                                        <input type="checkbox" id="originEscoto" value="Escoto"> Escoto<br>
                                        <input type="checkbox" id="originTNCHYard" value="TNCH Yard"> TNCH Yard<br>
                                        <input type="checkbox" id="originTNLExpress" value="TNL Express"> TNL Express<br>
                                        <input type="checkbox" id="originTNCH" value="TNCH"> TNCH<br>
                                        <input type="checkbox" id="originTNL" value="TNL"> TNL<br>
                                        <input type="checkbox" id="originFEMA" value="FEMA"> FEMA<br>
                                    </div>
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyoriginfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Trailer ID -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplytraileridfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapstraileridfilter" aria-expanded="false" aria-controls="multiCollapstraileridfilter">Trailer ID</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapstraileridfilter">
                                    <input type="text" class="form-control" id="inputapplytraileridfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applytraileridfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Destination -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydestinationfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsdestinationfilter" aria-expanded="false" aria-controls="multiCollapsdestinationfilter">Destination</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsdestinationfilter">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Miami" id="destinationMiami">
                                        <label class="form-check-label" for="destinationMiami">Miami</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="On Time Forwarding" id="destinationOnTimeForwarding">
                                        <label class="form-check-label" for="destinationOnTimeForwarding">On Time Forwarding</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="TFEMA Yard" id="destinationTFEMAYard">
                                        <label class="form-check-label" for="destinationTFEMAYard">TFEMA Yard</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="KN" id="destinationKN">
                                        <label class="form-check-label" for="destinationKN">KN</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="TNL Express" id="destinationTNLExpress">
                                        <label class="form-check-label" for="destinationTNLExpress">TNL Express</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Escoto" id="destinationEscoto">
                                        <label class="form-check-label" for="destinationEscoto">Escoto</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="TNCH YARD" id="destinationTNCHYard">
                                        <label class="form-check-label" for="destinationTNCHYard">TNCH YARD</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="K&N" id="destinationKNandN">
                                        <label class="form-check-label" for="destinationKNandN">K&N</label>
                                    </div>
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydestinationfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Pre-Alert Date & Time -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyprealertfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsprealertfilter" aria-expanded="false" aria-controls="multiCollapsprealertfilter">
                            Pre-Alert Date & Time
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsprealertfilter">
                                    <label for="prealertFrom">From:</label>
                                    <input type="date" class="form-control" id="prealertFrom">

                                    <label for="prealertTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="prealertTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyprealertfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Carrier Dropping Trailer -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplycarrierfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapscarrierfilter" aria-expanded="false" aria-controls="multiCollapscarrierfilter">Carrier Dropping Trailer</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapscarrierfilter">
                                    <div>
                                        <input type="checkbox" id="carrierNWT" value="NWT"> NWT<br>
                                        <input type="checkbox" id="carrierESTrucking" value="ES Trucking"> ES Trucking<br>
                                        <input type="checkbox" id="carrierMiamiCargoCorp" value="Miami Cargo Corp"> Miami Cargo Corp<br>
                                        <input type="checkbox" id="carrierDKTransport" value="DK Transport"> DK Transport<br>
                                        <input type="checkbox" id="carrierEmpty" value="empty"> (empty)<br>
                                        <input type="checkbox" id="carrierLandstar" value="Landstar"> Landstar<br>
                                        <input type="checkbox" id="carrierMVT" value="MVT"> MVT<br>
                                        <input type="checkbox" id="carrierTFEMA" value="TFEMA"> TFEMA<br>
                                        <input type="checkbox" id="carrierTNLExpress" value="TNL Express"> TNL Express<br>
                                        <input type="checkbox" id="carrierTNCH" value="TNCH"> TNCH<br>
                                        <input type="checkbox" id="carrierTGATransport" value="TGA Transport"> TGA Transport<br>
                                    </div>
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applycarrierfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Trailer Owner -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplytrailerownerfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapstrailerownerfilter" aria-expanded="false" aria-controls="multiCollapstrailerownerfilter">Trailer Owner</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapstrailerownerfilter">
                                    <div>
                                        <input type="checkbox" id="trailerOwnerNWT" value="NWT"> NWT<br>
                                        <input type="checkbox" id="trailerOwnerESTrucking" value="ES Trucking"> ES Trucking<br>
                                        <input type="checkbox" id="trailerOwnerMiamiCargoCorp" value="Miami Cargo Corp"> Miami Cargo Corp<br>
                                        <input type="checkbox" id="trailerOwnerDKTransport" value="DK Transport"> DK Transport<br>
                                        <input type="checkbox" id="trailerOwnerEmpty" value="empty"> (empty)<br>
                                        <input type="checkbox" id="trailerOwnerLandstar" value="Landstar"> Landstar<br>
                                        <input type="checkbox" id="trailerOwnerMVT" value="MVT"> MVT<br>
                                        <input type="checkbox" id="trailerOwnerTFEMA" value="TFEMA"> TFEMA<br>
                                        <input type="checkbox" id="trailerOwnerTNLExpress" value="TNL Express"> TNL Express<br>
                                        <input type="checkbox" id="trailerOwnerTNCH" value="TNCH"> TNCH<br>
                                        <input type="checkbox" id="trailerOwnerTGA" value="TGA"> TGA<br>
                                    </div>
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applytrailerownerfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Driver -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydriverfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsdriverfilter" aria-expanded="false" aria-controls="multiCollapsdriverfilter">Driver</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsdriverfilter">
                                    <input type="text" class="form-control" id="inputapplydriverfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydriverfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Truck -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplytruckfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapstruckfilter" aria-expanded="false" aria-controls="multiCollapstruckfilter">Truck</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapstruckfilter">
                                    <input type="text" class="form-control" id="inputapplytruckfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applytruckfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Suggested Delivery Date -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplysuggesteddeliverydatefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapssuggesteddeliverydatefilter" aria-expanded="false" aria-controls="multiCollapssuggesteddeliverydatefilter">
                            Suggested Delivery Date
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapssuggesteddeliverydatefilter">
                                    <label for="suggesteddeliverydateFrom">From:</label>
                                    <input type="date" class="form-control" id="suggesteddeliverydateFrom">

                                    <label for="suggesteddeliverydateTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="suggesteddeliverydateTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applysuggesteddeliverydatefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Units -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyunitsfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseunitsfilter" aria-expanded="false" aria-controls="multiCollapseunitsfilter">Units</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseunitsfilter">
                                    <input type="text" class="form-control" id="inputapplyunitsfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyunitsfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Pallets -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplypalletsfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsepalletsfilter" aria-expanded="false" aria-controls="multiCollapsepalletsfilter">Pallets</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsepalletsfilter">
                                    <input type="text" class="form-control" id="inputapplypalletsfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applypalletsfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Seal 1 -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyseal1filter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseseal1filter" aria-expanded="false" aria-controls="multiCollapseseal1filter">Seal 1</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseseal1filter">
                                    <input type="text" class="form-control" id="inputapplyseal1filter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyseal1filter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Seal 2 -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyseal2filter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseseal2filter" aria-expanded="false" aria-controls="multiCollapseseal2filter">Seal 2</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseseal2filter">
                                    <input type="text" class="form-control" id="inputapplyseal2filter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyseal2filter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Notes -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplynotesfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsenotesfilter" aria-expanded="false" aria-controls="multiCollapsenotesfilter">Notes</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsenotesfilter">
                                    <input type="text" class="form-control" id="inputapplynotesfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applynotesfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Current Status -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplycurrentstatusfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapscurrentstatusfilter" aria-expanded="false" aria-controls="multiCollapscurrentstatusfilter">Current Status</button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapscurrentstatusfilter">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Finalized" id="currentStatusFinalized">
                                        <label class="form-check-label" for="currentStatusFinalized">Finalized</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="In Transit" id="currentStatusInTransit">
                                        <label class="form-check-label" for="currentStatusInTransit">In Transit</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Delivered" id="currentStatusDelivered">
                                        <label class="form-check-label" for="currentStatusDelivered">Delivered</label>
                                    </div>
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applycurrentstatusfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Dock Door Date -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydockdoordatefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsdockdoordatefilter" aria-expanded="false" aria-controls="multiCollapsdockdoordatefilter">
                            Dock Door Date
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsdockdoordatefilter">
                                    <label for="dockdoordateFrom">From:</label>
                                    <input type="date" class="form-control" id="dockdoordateFrom">

                                    <label for="dockdoordateTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="dockdoordateTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydockdoordatefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Driver Assigned Date -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydriverassigneddatefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsdriverassigneddatefilter" aria-expanded="false" aria-controls="multiCollapsdriverassigneddatefilter">
                            Driver Assigned Date
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsdriverassigneddatefilter">
                                    <label for="driverassigneddateFrom">From:</label>
                                    <input type="date" class="form-control" id="driverassigneddateFrom">

                                    <label for="driverassigneddateTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="driverassigneddateTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydriverassigneddatefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Pick Up Date -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplypickupdatefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapspickupdatefilter" aria-expanded="false" aria-controls="multiCollapspickupdatefilter">
                            Pick Up Date
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapspickupdatefilter">
                                    <label for="pickupdateFrom">From:</label>
                                    <input type="date" class="form-control" id="pickupdateFrom">

                                    <label for="pickupdateTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="pickupdateTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applypickupdatefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por In Transit Date -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyintransitdatefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsintransitdatefilter" aria-expanded="false" aria-controls="multiCollapsintransitdatefilter">
                            In Transit Date
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsintransitdatefilter">
                                    <label for="intransitdateFrom">From:</label>
                                    <input type="date" class="form-control" id="intransitdateFrom">

                                    <label for="intransitdateTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="intransitdateTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyintransitdatefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Filtro por Delivered Date -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydelivereddatefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseDeliveredDateFilter" aria-expanded="false" aria-controls="multiCollapseDeliveredDateFilter">
                            Delivered Date
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseDeliveredDateFilter">
                                    <label for="delivereddateFrom">From:</label>
                                    <input type="date" class="form-control" id="delivereddateFrom">

                                    <label for="delivereddateTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="delivereddateTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydelivereddatefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Secured Yard Date -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplysecuredyarddatefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapssecuredyarddatefilter" aria-expanded="false" aria-controls="multiCollapssecuredyarddatefilter">
                            Secured Yard Date
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapssecuredyarddatefilter">
                                    <label for="securedyarddateFrom">From:</label>
                                    <input type="date" class="form-control" id="securedyarddateFrom">

                                    <label for="securedyarddateTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="securedyarddateTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applysecuredyarddatefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por WH Auth Date -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplywhauthdatefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapswhauthdatefilter" aria-expanded="false" aria-controls="multiCollapswhauthdatefilter">
                            Approved ETA date
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapswhauthdatefilter">
                                    <label for="whauthdateFrom">From:</label>
                                    <input type="date" class="form-control" id="whauthdateFrom">

                                    <label for="whauthdateTo" class="mt-2">To:</label>
                                    <input type="date" class="form-control" id="whauthdateTo">

                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applywhauthdatefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Filtro por At Door Time -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyatdoortimefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapsatdoortimefilter" aria-expanded="false" aria-controls="multiCollapsatdoortimefilter">
                            At Door Time
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapsatdoortimefilter">
                                    <input type="text" class="form-control" id="inputapplyatdoortimefilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyatdoortimefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Door Number -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplydoornumberfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseDoorNumberFilter" aria-expanded="false" aria-controls="multiCollapseDoorNumberFilter">
                            Door Number
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseDoorNumberFilter">
                                    <input type="text" class="form-control" id="inputapplydoornumberfilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applydoornumberfilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Offload Time -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplyoffloadtimefilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseOffloadTimeFilter" aria-expanded="false" aria-controls="multiCollapseOffloadTimeFilter">
                            Offload Time
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseOffloadTimeFilter">
                                    <input type="time" class="form-control" id="inputapplyoffloadtimefilter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applyoffloadtimefilter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Tracker 1 -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplytracker1filter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseTracker1Filter" aria-expanded="false" aria-controls="multiCollapseTracker1Filter">
                            Tracker 1
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseTracker1Filter">
                                    <input type="text" class="form-control" id="inputapplytracker1filter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applytracker1filter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Tracker 2 -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplytracker2filter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseTracker2Filter" aria-expanded="false" aria-controls="multiCollapseTracker2Filter">
                            Tracker 2
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseTracker2Filter">
                                    <input type="text" class="form-control" id="inputapplytracker2filter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applytracker2filter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro por Tracker 3 -->
                    <div>
                        <button class="btn btn-primary w-100 mb-2" id="closeapplytracker3filter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseTracker3Filter" aria-expanded="false" aria-controls="multiCollapseTracker3Filter">
                            Tracker 3
                        </button>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseTracker3Filter">
                                    <input type="text" class="form-control" id="inputapplytracker3filter">
                                    <button class="btn btn-primary mt-2 filterapply" type="button" id="applytracker3filter">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                                <!-- Filtro por Security Company ID -->
                        <div>
                            <button class="btn btn-primary w-100 mb-2" id="closeapplysecuritycompanyidfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseSecurityCompanyIDFilter" aria-expanded="false" aria-controls="multiCollapseSecurityCompanyIDFilter">
                                Security Company ID
                            </button>
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="collapse multi-collapse" id="multiCollapseSecurityCompanyIDFilter">
                                        <input type="text" class="form-control" id="inputapplysecuritycompanyidfilter">
                                        <button class="btn btn-primary mt-2 filterapply" type="button" id="applysecuritycompanyidfilter">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtro por Security Company -->
                        <div>
                            <button class="btn btn-primary w-100 mb-2" id="closeapplysecuritycompanyfilter" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseSecurityCompanyFilter" aria-expanded="false" aria-controls="multiCollapseSecurityCompanyFilter">
                                Security Company
                            </button>
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="collapse multi-collapse" id="multiCollapseSecurityCompanyFilter">
                                        <input type="text" class="form-control" id="inputapplysecuritycompanyfilter">
                                        <button class="btn btn-primary mt-2 filterapply" type="button" id="applysecuritycompanyfilter">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <div class="col-auto mt-2" id="activeFilterDiv" style="display:none;">


            </div>

            </div>
                <div class="table-responsive">
                <table class="table" id="shipmentsTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Shipment Type</th>
                            <th>STM ID</th>
                            <th>Secondary Shipment ID</th>
                            <th>Carrier Reference</th>
                            <th>Origin</th>
                            <th>Trailer ID</th>
                            <th>Destination</th>
                            <th>Pre-Alert Date & Time</th>
                            <th>Carrier Dropping Trailer</th>
                            <th>Trailer Owner</th>
                            <th>Driver</th>
                            <th>Truck</th>
                            <th>Suggested Delivery Date</th>
                            <th>Units</th>
                            <th>Pallets</th>
                            <th>Seal 1</th>
                            <th>Seal 2</th>
                            <th>Notes</th>
                            <th>Current Status</th>
                            <th>Dock Door Date</th>
                            <th>Driver Assigned Date</th>
                            <th>Picked Up Date</th>
                            <th>In Transit Date</th>
                            <th>Delivered/Received Date</th>
                            <th>Secured Yard Date</th>
                            <th>Approved ETA Date & Time</th>
                            <th>Sec Incident</th>
                            <th>Incident Type</th>
                            <th>Incident Date</th>
                            <th>Incident Notes</th>
                            <th>WH Status</th>
                            <th>At Door Time</th>
                            <th>Door Number</th>
                            <th>Offload Time</th>
                            <!--<th>Date of Billing</th>  -->
                            <!--<th>Billing ID</th>  -->
                            <th>Tracker 1</th>
                            <th>Tracker 2</th>
                            <th>Tracker 3</th>
                            <th>Security Company ID</th>
                            <th>Security Company</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                        <tr data-bs-toggle="modal" data-bs-target="#shipmentModal{{ $shipment->stm_id }}" class="clickable-row" data-shipment-id="{{ $shipment->stm_id }}">
                            <td>{{ $shipment->shipmentType->gntc_description ?? '' }}</td>
                            <td>{{ $shipment->stm_id ?? '' }}</td>
                            <td>{{ $shipment->secondary_shipment_id ?? '' }}</td>
                            <td>{{ $shipment->reference ?? '' }}</td>
                            <td>{{ $shipment->origin ?? '' }}</td>
                            <td>{{ $shipment->id_trailer ?? '' }}</td>
                            <td>{{ $shipment->destination ?? '' }}</td>
                            <td>{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') : '' }}</td>
                            <td>{{ $shipment->company->CoName ?? '' }}</td>
                            <td>{{ $shipment->trailer ?? '' }}</td>
                            <td>{{ $shipment->driver->drivername ?? '' }}</td>
                            <td>{{ $shipment->truck ?? '' }}</td>
                            <td>{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->units ?? '' }}</td>
                            <td>{{ $shipment->pallets ?? '' }}</td>
                            <td>{{ $shipment->seal1 ?? '' }}</td>
                            <td>{{ $shipment->seal2 ?? '' }}</td>
                            <td>{{ $shipment->notes ?? '' }}</td>
                            <td>{{ $shipment->currentStatus->gntc_value ?? '' }}</td>
                            <td>{{ $shipment->dock_door_date ? \Carbon\Carbon::parse($shipment->dock_door_date)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->sec_incident ?? '' }}</td>
                            <td>{{ $shipment->incident_type ?? '' }}</td>
                            <td>{{ $shipment->incident_date ? \Carbon\Carbon::parse($shipment->incident_date)->format('m/d/Y H:i:s') : '' }}</td>
                            <td>{{ $shipment->incident_notes ?? '' }}</td>
                            <td>{{ $shipment->wh_status ?? '' }}</td>
                            <td>{{ $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('H:i') : '' }}</td>
                            <td>{{ $shipment->door_number ?? '' }}</td>
                            <td>{{ $shipment->offloading_time ? \Carbon\Carbon::parse($shipment->offloading_time)->format('H:i') : '' }}</td>
                            <td>{{ $shipment->tracker1 ?? '' }}</td>
                            <td>{{ $shipment->tracker2 ?? '' }}</td>
                            <td>{{ $shipment->tracker3 ?? '' }}</td>
                            <td>{{ $shipment->security_company_id ?? '' }}</td>
                            <td>{{ $shipment->security_company ?? '' }}</td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para ver los detalles del envío -->
        @foreach ($shipments as $shipment)
        <div class="modal fade" id="shipmentModal{{ $shipment->stm_id }}" tabindex="-1" aria-labelledby="shipmentModalLabel{{ $shipment->stm_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #0056b3;" >
                        <h5 class="modal-title" id="shipmentModalLabel{{ $shipment->stm_id }}">Shipment Details - {{ $shipment->stm_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Pestañas de detalle -->
                        <ul class="nav nav-pills mb-3" id="pills-tab{{ $shipment->stm_id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-shipment-details-tab{{ $shipment->stm_id }}"
                                   data-bs-toggle="pill" href="#pills-shipment-details{{ $shipment->stm_id }}"
                                   role="tab" aria-controls="pills-shipment-details{{ $shipment->stm_id }}"
                                   aria-selected="true"> Initial Shipment Info</a>
                            </li>
                            <li class="nav-item mx-2" role="presentation">
                                <a class="nav-link" id="pills-update-status-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-update-status{{ $shipment->stm_id }}" role="tab" aria-controls="pills-update-status{{ $shipment->stm_id }}" aria-selected="false">Update Shipment Status</a>
                            </li>
                            <li class="nav-item mx-2" role="presentation">
                                <a class="nav-link" id="pills-shipment-info-tab{{ $shipment->stm_id }}" data-bs-toggle="pill" href="#pills-shipment-info-{{ $shipment->stm_id }}" role="tab" aria-controls="pills-shipment-info-{{ $shipment->stm_id }}" aria-selected="false">Shipment Details</a>
                            </li>
                        </ul>

                        <!-- Contenedor de contenido -->
                    <form id="shipmentForm-{{ $shipment->stm_id }}" method="POST" action="/update-status-endpoint/{{ $shipment->pk_shipment }}" onsubmit="return validateShipment('{{ $shipment->stm_id }}')">
                        <div class="tab-content" id="pills-tabContent{{ $shipment->stm_id }}">
                            <div class="tab-pane fade show active" id="pills-shipment-details{{ $shipment->stm_id }}"
                                role="tabpanel" aria-labelledby="pills-shipment-details-tab{{ $shipment->stm_id }}">

                                <div class="mb-3">
                                    <label for="stm_id" class="form-label">STM ID</label>
                                    <input type="text" class="form-control" id="stm_id" value="{{ $shipment->stm_id ?? 'STM ID Not Available' }}" readonly data-original="{{ $shipment->stm_id ?? 'STM ID not available' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="tracker1" class="form-label">Tracker 1</label>
                                    <input type="text" class="form-control" id="tracker1" name="tracker1" value="{{ $shipment->tracker1 }}" data-original="{{ $shipment->tracker1 }}">
                                </div>

                                <div class="mb-3">
                                    <label for="tracker2" class="form-label">Tracker 2</label>
                                    <input type="text" class="form-control" id="tracker2" name="tracker2" value="{{ $shipment->tracker2 }}" data-original="{{ $shipment->tracker2 }}">
                                </div>

                                <div class="mb-3">
                                    <label for="tracker3" class="form-label">Tracker 3</label>
                                    <input type="text" class="form-control" id="tracker3" name="tracker3" value="{{ $shipment->tracker3 }}" data-original="{{ $shipment->tracker3 }}">
                                </div>


                                <div class="mb-3">
                                    <label for="security_company_id" class="form-label">Security Company ID</label>
                                    <input type="text" class="form-control" id="security_company_id" name="security_company_id" value="{{ $shipment->security_company_id }}" data-original="{{ $shipment->security_company_id }}">
                                </div>

                                <div class="mb-3">
                                    <label for="securityCompany" class="form-label">Security Company</label>
                                    <input type="text" class="form-control" id="securityCompany-{{ $shipment->stm_id }}"
                                           value="{{ $shipment->securityCompany->gntc_description ?? '-- No Company Selected --' }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="secondary_shipment_id" class="form-label">Secondary Shipment ID</label>
                                    <input type="text" class="form-control" id="secondary_shipment_id" name="secondary_shipment_id" value="{{ $shipment->secondary_shipment_id }}" data-original="{{ $shipment->secondary_shipment_id }}">
                                </div>

                                <div class="mb-3">
                                    <label for="reference" class="form-label">Carrier Reference</label>
                                    <input type="text" class="form-control" id="reference" name="reference" value="{{ $shipment->reference }}" data-original="{{ $shipment->reference }}">
                                </div>

                                <div class="mb-3">
                                    <label for="shipment_type" class="form-label">Shipment Type</label>
                                    <input type="text" class="form-control" id="shipment_type" name="shipment_type" value="{{ $shipment->shipmentType->gntc_description ?? 'Not Available' }}" data-original="{{ $shipment->shipmentType->gntc_description ?? 'No disponible' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="etd-{{ $shipment->stm_id }}" class="form-label">ETD</label>
                                    <input type="text" class="form-control flatpickr" id="etd-{{ $shipment->stm_id }}" name="etd"
                                        value="{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--" data-original="{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i') : '' }}">
                                </div>

                               <!-- Origin -->
                                <div class="mb-3">
                                    <label for="origin-{{ $shipment->stm_id }}" class="form-label">Origin</label>
                                    <input type="text" class="form-control" id="origin-{{ $shipment->stm_id }}" name="origin" value="{{ $shipment->origin }}" readonly>
                                </div>

                                <!-- Destination -->
                                <div class="mb-3">
                                    <label for="destination-{{ $shipment->stm_id }}" class="form-label">Destination</label>
                                    <input type="text" class="form-control" id="destination-{{ $shipment->stm_id }}" name="destination" value="{{ $shipment->destination }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="preAlertedDatetime-{{ $shipment->stm_id }}" class="form-label">Pre-Alerted Datetime</label>
                                    <input type="text" class="form-control flatpickr" id="preAlertedDatetime-{{ $shipment->stm_id }}" name="pre_alerted_datetime"
                                        value="{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--" data-original="{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i') : '' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="id_trailer-{{ $shipment->stm_id }}" class="form-label">Trailer ID</label>
                                    <input type="text" class="form-control" id="id_trailer-{{ $shipment->stm_id }}" name="id_trailer"
                                           value="{{ old('id_trailer', $shipment->id_trailer) }}" placeholder="Enter Trailer ID" data-original="{{ old('id_trailer', $shipment->id_trailer) }}">
                                </div>

                                <!-- Trailer Owner -->
                                <div class="mb-3">
                                    <label for="trailer_owner-{{ $shipment->stm_id }}" class="form-label">Trailer Owner</label>
                                    <select class="form-select" id="trailer_owner-{{ $shipment->stm_id }}" name="trailer_owner" data-original="{{ $shipment->trailer_owner }}">
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->pk_company }}"
                                                {{ old('trailer_owner', $shipment->origin) == $company->pk_company ? 'selected' : '' }}>
                                                {{ $company->CoName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Carrier -->
                                <div class="mb-3">
                                    <label for="carrier-{{ $shipment->stm_id }}" class="form-label">Carrier Dropping Trailer</label>
                                    <select class="form-select" id="carrier-{{ $shipment->stm_id }}" name="carrier" data-original="{{ $shipment->carrier }}">
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->pk_company }}"
                                                {{ old('carrier', $shipment->origin) == $company->pk_company ? 'selected' : '' }}>
                                                {{ $company->CoName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="driver-{{ $shipment->stm_id }}" class="form-label">Driver</label>
                                    <select class="form-select" id="driver-{{ $shipment->stm_id }}" name="id_driver" data-original="{{ $shipment->id_driver }}">
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id_driver }}"
                                                {{ old('id_driver', $shipment->id_driver) == $driver->id_driver ? 'selected' : '' }}>
                                                {{ $driver->drivername }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="Truck" class="form-label">Truck</label>
                                    <input type="text" class="form-control" id="Truck" name="truck" value="{{ $shipment->truck }}" data-original="{{ $shipment->truck }}">
                                </div>

                                <div class="mb-3">
                                    <label for="units-{{ $shipment->stm_id }}" class="form-label">Units</label>
                                    <input type="text" class="form-control" id="units-{{ $shipment->stm_id }}" name="units" value="{{ $shipment->units }}" oninput="validateShipment('{{ $shipment->stm_id }}')" data-original="{{ $shipment->units }}">
                                </div>

                                <div class="mb-3">
                                    <label for="pallets-{{ $shipment->stm_id }}" class="form-label">Pallets</label>
                                    <input type="text" class="form-control" id="pallets-{{ $shipment->stm_id }}" name="pallets" value="{{ $shipment->pallets }}" oninput="validateShipment('{{ $shipment->stm_id }}')" data-original="{{ $shipment->pallets }}">
                                </div>

                                <span id="error-message-{{ $shipment->stm_id }}" style="color: red; display: none;"></span>

                                <div class="mb-3">
                                    <label for="seal1" class="form-label">Seal 1</label>
                                    <input type="text" class="form-control" id="seal1" name="seal1" value="{{ $shipment->seal1 }}" data-original="{{ $shipment->seal1 }}">
                                </div>

                                <div class="mb-3">
                                    <label for="seal2" class="form-label">Seal 2</label>
                                    <input type="text" class="form-control" id="seal2" name="seal2" value="{{ $shipment->seal2 }}" data-original="{{ $shipment->seal2 }}">
                                </div>

                                <div class="mb-3" hidden>
                                    <label for="lane" class="form-label">Lane</label>
                                    <input type="text" class="form-control" id="lane" name="lane" value="{{ $shipment->lane }}" data-original="{{ $shipment->lane }}">
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" data-original="{{ $shipment->notes }}">{{ $shipment->notes }}</textarea>
                                </div>


                                    <!-- Agrega más campos si es necesario -->
                                    <button id="nextButton-{{ $shipment->stm_id }}" class="btn btn-primary" type="button">Next</button>
                            </div>
                            <div class="tab-pane fade" id="pills-update-status{{ $shipment->stm_id }}"
                                role="tabpanel" aria-labelledby="pills-update-status-tab{{ $shipment->stm_id }}">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <label for="currentStatus" class="form-label">Current Status</label>
                                <select class="form-select" id="currentStatus-{{ $shipment->stm_id }}" name="gnct_id_current_status" data-original="{{ old('gnct_id_current_status', $shipment->gnct_id_current_status) }}">
                                    @foreach ($currentStatus as $status)
                                        <option value="{{ $status->gnct_id }}"
                                            {{ old('gnct_id_current_status', $shipment->gnct_id_current_status) == $status->gnct_id ? 'selected' : '' }}>
                                            {{ $status->gntc_description }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="mb-3">
                                    <label for="dockDoorDate-{{ $shipment->stm_id }}" class="form-label">Dock Door Date</label>
                                    <input type="text" class="form-control flatpickr" id="dockDoorDate-{{ $shipment->stm_id }}" name="dock_door_date"
                                        value="{{ $shipment->dock_door_date ? \Carbon\Carbon::parse($shipment->dock_door_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->dock_door_date ? \Carbon\Carbon::parse($shipment->dock_door_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('dockDoorDate-{{ $shipment->stm_id }}', 'Dock Door', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="driverAssignmentDate-{{ $shipment->stm_id }}" class="form-label">Driver Assignment Date</label>
                                    <input type="text" class="form-control flatpickr" id="driverAssignmentDate-{{ $shipment->stm_id }}" name="driver_assigned_date"
                                        value="{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('driverAssignmentDate-{{ $shipment->stm_id }}', 'Driver Assigned', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="pickUpDate-{{ $shipment->stm_id }}" class="form-label">Pick Up Date</label>
                                    <input type="text" class="form-control flatpickr" id="pickUpDate-{{ $shipment->stm_id }}" name="pick_up_date"
                                        value="{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('pickUpDate-{{ $shipment->stm_id }}', 'Picked Up', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="inTransitDate-{{ $shipment->stm_id }}" class="form-label">In Transit Date</label>
                                    <input type="text" class="form-control flatpickr" id="inTransitDate-{{ $shipment->stm_id }}" name="intransit_date"
                                        value="{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('inTransitDate-{{ $shipment->stm_id }}', 'In Transit', '{{ $shipment->stm_id }}')">
                                </div>

                                <div class="mb-3">
                                    <label for="securedYardDate-{{ $shipment->stm_id }}" class="form-label">Secured Yard Date</label>
                                    <input type="text" class="form-control flatpickr" id="securedYardDate-{{ $shipment->stm_id }}" name="secured_yarddate"
                                        value="{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i') : '' }}"
                                        placeholder="mm/dd/yyyy --:--"
                                        data-original="{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i') : '' }}"
                                        onfocus="checkAndChangeStatus('securedYardDate-{{ $shipment->stm_id }}', 'Secured Yard', '{{ $shipment->stm_id }}')">
                                </div>

                                <!-- Campos de Incidentes desactivados para pruebas -->
                                <div class="mb-3">
                                    <label for="secIncident-{{ $shipment->stm_id }}" class="form-label">Sec Incident</label>
                                    <select class="form-select" id="secIncident-{{ $shipment->stm_id }}" name="sec_incident" disabled data-original="null">
                                        <option value="null" selected>No Incident</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="incidentType-{{ $shipment->stm_id }}" class="form-label">Incident Type</label>
                                    <input type="text" class="form-control" id="incidentType-{{ $shipment->stm_id }}" name="incident_type" disabled placeholder="Type of incident (if any)" data-original="">
                                </div>
                                <div class="mb-3">
                                    <label for="incidentDate-{{ $shipment->stm_id }}" class="form-label">Incident Date</label>
                                    <input type="datetime-local" class="form-control" id="incidentDate-{{ $shipment->stm_id }}" name="incident_date" disabled data-original="">
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                    </form>


                            </div>
                            <div class="tab-pane fade"
                                id="pills-shipment-info-{{ $shipment->stm_id }}"
                                role="tabpanel"
                                aria-labelledby="pills-shipment-info-tab{{ $shipment->stm_id }}">
                                <form id="shipmentDetailsForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">STM ID</label>
                                        <p>{{ $shipment->stm_id ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Reference</label>
                                        <p>{{ $shipment->reference ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bonded</label>
                                        <p>{{ $shipment->bonded ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Origin</label>
                                        <p>{{ $shipment->origin ?? 'Origin not available' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Destination</label>
                                        <p>{{ $shipment->destination ?? 'Not available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pre-Alerted Date & Time</label>
                                        <p>{{ $shipment->pre_alerted_datetime ? \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Trailer ID</label>
                                        <p>{{ $shipment->id_trailer ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Company</label>
                                        <p>{{ $shipment->company->CoName ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Trailer</label>
                                        <p>{{ $shipment->trailer ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Truck</label>
                                        <p>{{ $shipment->truck ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Driver ID</label>
                                        <p>{{ $shipment->id_driver ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">ETD (Estimated Time of Departure)</label>
                                        <p>{{ $shipment->etd ? \Carbon\Carbon::parse($shipment->etd)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Units</label>
                                        <p>{{ $shipment->units ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pallets</label>
                                        <p>{{ $shipment->pallets ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Seal 1</label>
                                        <p>{{ $shipment->seal1 ?? 'Not Available' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Seal 2</label>
                                        <p>{{ $shipment->seal2 ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Security Company ID</label>
                                        <p>{{ $shipment->security_company_id ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Security Company</label>
                                        <p>{{ $shipment->securityCompany->gntc_description ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tracker 1</label>
                                        <p>{{ $shipment->tracker1 ?? 'Not Available' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tracker 2</label>
                                        <p>{{ $shipment->tracker2 ?? 'Not Available' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tracker 3</label>
                                        <p>{{ $shipment->tracker3 ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Secondary Shipment ID</label>
                                        <p>{{ $shipment->secondary_shipment_id ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Driver Assigned Date</label>
                                        <p>{{ $shipment->driver_assigned_date ? \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pick-Up Date</label>
                                        <p>{{ $shipment->pick_up_date ? \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">In Transit Date</label>
                                        <p>{{ $shipment->intransit_date ? \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Secured Yard Date</label>
                                        <p>{{ $shipment->secured_yarddate ? \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Current Status</label>
                                        <p>{{ $shipment->currentStatus->gntc_description ?? 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Shipment Type </label>
                                        <p>{{ $shipment->shipmentType->gntc_description ?? 'Not Availible' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Delivered Date</label>
                                        <p>{{ $shipment->delivered_date ? \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">At Door Date</label>
                                        <p>{{ $shipment->at_door_date ? \Carbon\Carbon::parse($shipment->at_door_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Approved ETA date & Time</label>
                                        <p>{{ $shipment->wh_auth_date ? \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y H:i:s') : 'Not Available' }}</p>
                                    </div>
                                </form>

                            </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach


    @endauth

    @guest
        <p>Para ver el contenido <a href="/login">Inicia Sesión</a></p>
    @endguest
@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchByShipment');
        const table = document.getElementById('shipmentsTable');
        const rows = table.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function () {
            const searchText = this.value.toLowerCase();

            rows.forEach(row => {
                // Verificar si alguna celda contiene el texto buscado
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell =>
                    cell.textContent.toLowerCase().includes(searchText)
                );

                // Mostrar u ocultar la fila dependiendo de si coincide
                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>

<script>
    $(document).on('submit', '[id^="shipmentForm"]', function (event) {
        event.preventDefault(); // Previene el envío estándar del formulario
        console.log('Formulario enviado (delegado)');

        let formAction = $(this).attr('action');
        let formData = $(this).serialize();

        $.ajax({
            url: formAction,
            method: 'PUT',
            data: formData,
            beforeSend: function () {
                Swal.fire({
                    title: 'Enviando datos...',
                    text: 'Por favor espera mientras se procesan los datos.',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading(); // Muestra un indicador de carga
                    }
                });
            },
            success: function (response) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: response.message || 'Los datos se actualizaron correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload(); // Recargar la página para reflejar los cambios
                });
                console.log('Respuesta recibida:', response);
            },
            error: function (xhr) {
                let errorMessage = xhr.responseJSON?.message || 'Ocurrió un error al actualizar el estado.';
                Swal.fire({
                    title: 'Error',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                console.error('Error en la solicitud:', xhr.responseJSON || xhr.responseText);
            },
        });
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tableRows = document.querySelectorAll("#shipmentsTable tbody tr"); // Filas de la tabla
    let activeFilters = []; // Para almacenar los filtros activos

    // Función para crear los divs de los filtros activos
    function createFilterDiv(filterId, text) {
        return `<div id="${filterId}" style="background-color: rgb(13, 82, 200); border-radius: 0.5rem; display: inline-block; padding: 0.2rem 0.5rem; margin-right: 5px; color: white;">
                    <span>${text}</span>
                    <button style="background-color: unset; color: white; font-size: small; border: none; cursor: pointer;">X</button>
                </div>`;
    }

    // Función que aplica todos los filtros activos
    // Función para aplicar TODOS los filtros activos (texto, fecha y checkbox)
    function applyFilters() {
    console.log("📌 Filtros activos:", activeFilters); // Ver todos los filtros antes de aplicarlos

    tableRows.forEach(row => {
        let showRow = true;

        activeFilters.forEach(filter => {
            const cell = row.cells[filter.columnIndex];
            if (!cell) return;

            const cellText = cell.textContent.trim().toLowerCase();

            if (filter.type === "text") {
                showRow = showRow && cellText.includes(filter.value.toLowerCase());
            }

            if (filter.type === "checkbox") {
                console.log("⏳ Filtrando checkbox en columna", filter.columnIndex, "valor:", cellText);
                console.log("✔️ Valores permitidos:", filter.values);

                showRow = showRow && filter.values.includes(cellText);
            }

            if (filter.type === "date") {
                const cellDate = new Date(cell.textContent.trim());
                if (!isNaN(cellDate)) {
                    const fromDate = new Date(filter.from);
                    const toDate = new Date(filter.to);
                    showRow = showRow && (cellDate >= fromDate && cellDate <= toDate);
                } else {
                    showRow = false;
                }
            }
        });

        row.style.display = showRow ? "" : "none";
    });
}
    // Función que se ejecuta cuando se aplica el filtro de texto
    function applyTextFilter(inputId, columnIndex) {
        const input = document.getElementById(inputId);
        const filterValue = input.value.trim();
        if (filterValue) {
            activeFilters.push({ type: "text", value: filterValue, columnIndex: columnIndex });
            const filterDiv = createFilterDiv(`filter-text-${columnIndex}`, `${inputId}: ${filterValue}`);
            document.getElementById('activeFilterDiv').innerHTML += filterDiv;
            document.getElementById('activeFilterDiv').style.display = 'block'; // Asegurar que el contenedor se muestre
        }
        applyFilters();
    }

    // Función que se ejecuta cuando se aplica el filtro de checkbox
    function applyCheckboxFilter(checkboxIds, buttonId, columnIndex, filterType) {
    const applyButton = document.getElementById(buttonId);
    const checkboxes = checkboxIds.map(id => document.getElementById(id));
    const activeFilterDiv = document.getElementById('activeFilterDiv');

    if (applyButton) {
        applyButton.addEventListener("click", function () {
            const selectedValues = checkboxes
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value.toLowerCase());

            console.log("✔️ Checkbox seleccionados:", selectedValues);
            console.log("🔍 Antes de eliminar, activeFilters:", activeFilters);

            // Eliminar filtros previos de checkbox en esa columna
            activeFilters = activeFilters.filter(f => !(f.type === "checkbox" && f.columnIndex === columnIndex));

            console.log("❌ Después de eliminar, activeFilters:", activeFilters);

            if (selectedValues.length > 0) {
                activeFilters.push({
                    type: "checkbox",
                    values: selectedValues,
                    columnIndex: columnIndex
                });

                console.log("📌 Filtro agregado a activeFilters:", activeFilters);

                const filterDiv = createFilterDiv(`filter-checkbox-${columnIndex}`, `${filterType}: ${selectedValues.join(", ")}`);
                activeFilterDiv.innerHTML += filterDiv;
                activeFilterDiv.style.display = 'block';
            }

            applyFilters();
        });
    }
}
    // Función que se ejecuta cuando se aplica el filtro de fecha
    function applyDateFilter(fromId, toId, columnIndex) {
        const fromInput = document.getElementById(fromId);
        const toInput = document.getElementById(toId);
        const fromDate = fromInput.value ? new Date(fromInput.value + 'T00:00:00') : null;
        const toDate = toInput.value ? new Date(toInput.value + 'T23:59:59') : null;

        if (fromDate && toDate && !isNaN(fromDate) && !isNaN(toDate)) {
            activeFilters.push({
                type: "date",
                from: fromDate,
                to: toDate,
                columnIndex: columnIndex
            });
            const filterDiv = createFilterDiv(`filter-date-${columnIndex}`, `Fecha: desde ${fromInput.value} hasta ${toInput.value}`);
            document.getElementById('activeFilterDiv').innerHTML += filterDiv;
            document.getElementById('activeFilterDiv').style.display = 'block'; // Asegurar que el contenedor se muestre
        }
        applyFilters();
    }

    // Función para eliminar el filtro activo
    function removeFilter(filterId) {
        document.getElementById(filterId).remove();
        const filterIndex = activeFilters.findIndex(f => `filter-${f.type}-${f.columnIndex}` === filterId);
        if (filterIndex !== -1) {
            activeFilters.splice(filterIndex, 1); // Eliminar el filtro del array de filtros activos
        }
        applyFilters();
    }

    // Asignar evento a los botones de cierre de cada filtro activo
    document.getElementById('activeFilterDiv').addEventListener('click', function (event) {
        if (event.target.tagName.toLowerCase() === 'button') {
            const filterDiv = event.target.parentElement;
            const filterId = filterDiv.id;
            removeFilter(filterId);
        }
    });

    // **Aplicar filtro de texto genérico**
    function applyFilter(inputId, buttonId, columnIndex, filterType) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);

        if (button) {
            button.addEventListener("click", function () {
                const filterValue = input.value.trim().toLowerCase();
                if (filterValue) {
                    applyTextFilter(inputId, columnIndex);
                }
            });
        }
    }

    // **Aplicar filtro de checkbox**
    function applyCheckboxFilter(checkboxIds, buttonId, columnIndex, filterType) {
    const applyButton = document.getElementById(buttonId);
    const checkboxes = checkboxIds.map(id => document.getElementById(id));
    const activeFilterDiv = document.getElementById('activeFilterDiv');

    if (applyButton) {
        applyButton.addEventListener("click", function () {
            const selectedValues = checkboxes
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value.toLowerCase());

            console.log("✔️ Checkbox seleccionados:", selectedValues);

            // Eliminar filtros previos de checkbox en esa columna
            activeFilters = activeFilters.filter(f => !(f.type === "checkbox" && f.columnIndex === columnIndex));

            if (selectedValues.length > 0) {
                activeFilters.push({
                    type: "checkbox",
                    values: selectedValues,
                    columnIndex: columnIndex
                });

                console.log("📌 Filtro agregado a activeFilters:", activeFilters);

                const filterDiv = createFilterDiv(`filter-checkbox-${columnIndex}`, `${filterType}: ${selectedValues.join(", ")}`);
                activeFilterDiv.innerHTML += filterDiv;
                activeFilterDiv.style.display = 'block';
            }

            applyFilters();
        });
    }
}

    // **Aplicar filtro de fechas**
    function applyDateRangeFilter(fromId, toId, buttonId, columnIndex, filterType) {
        const fromInput = document.getElementById(fromId);
        const toInput = document.getElementById(toId);
        const applyButton = document.getElementById(buttonId);

        if (applyButton) {
            applyButton.addEventListener("click", function () {
                applyDateFilter(fromId, toId, columnIndex);
            });
        }
    }
    applyFilter('inputapplystmfilter', 'applystmfilter', 1, 'Shipment Type');
        applyFilter('inputapplysecondaryshipmentidfilter', 'applysecondaryshipmentidfilter', 2, 'Secondary Shipment ID');
        applyFilter('inputapplylandstarreferencefilter', 'applylandstarreferencefilter', 3, 'Landstar Reference');
        applyFilter('inputapplytraileridfilter', 'inputapplytraileridfilter', 5, 'Trailer ID');
        applyFilter('inputapplydriverfilter', 'applydriverfilter', 10, 'Driver');
        applyFilter('inputapplytruckfilter', 'applytruckfilter', 11, 'Truck');
        applyFilter('inputapplyunitsfilter', 'applyunitsfilter', 13, 'Units');
        applyFilter('inputapplypalletsfilter', 'applypalletsfilter', 14, 'Pallets');
        applyFilter('inputapplyseal1filter', 'applyseal1filter', 15, 'Seal 1');
        applyFilter('inputapplyseal2filter', 'applyseal2filter', 16, 'Seal 2');
        applyFilter('inputapplynotesfilter', 'applynotesfilter', 17, 'Notes');
        applyFilter('inputapplyatdoortimefilter', 'applyatdoortimefilter', 31, 'At Door Time');
        applyFilter('inputapplydoornumberfilter', 'applydoornumberfilter', 32, 'Door Number');
        applyFilter('inputapplyoffloadtimefilter', 'applyoffloadtimefilter', 33, 'Offload Time');
        applyFilter('inputapplytracker1filter', 'applytracker1filter', 34, 'Tracker 1');
        applyFilter('inputapplytracker2filter', 'applytracker2filter', 35, 'Tracker 2');
        applyFilter('inputapplytracker3filter', 'applytracker3filter', 36, 'Tracker 3');
        applyFilter('inputapplysecuritycompanyidfilter', 'applysecuritycompanyidfilter', 37, 'Security Company ID');
        applyFilter('inputapplysecuritycompanyfilter', 'applysecuritycompanyfilter', 38, 'Security Company');

        applyCheckboxFilter([
            'trailerOwnerNWT', 'trailerOwnerESTrucking', 'trailerOwnerMiamiCargoCorp', 'trailerOwnerDKTransport', 'trailerOwnerEmpty',
            'trailerOwnerLandstar', 'trailerOwnerMVT', 'trailerOwnerTFEMA', 'trailerOwnerTNLExpress', 'trailerOwnerTNCH', 'trailerOwnerTGA'
        ], 'applytrailerownerfilter', 8, 'Trailer Owner');

        applyCheckboxFilter([
            'carrierNWT', 'carrierESTrucking', 'carrierMiamiCargoCorp', 'carrierDKTransport', 'carrierEmpty',
            'carrierLandstar', 'carrierMVT', 'carrierTFEMA', 'carrierTNLExpress', 'carrierTNCH', 'carrierTGATransport'
        ], 'applycarrierfilter', 9, 'Carrier Dropping Trailer');

        applyCheckboxFilter([
            'originBW2', 'originBW3', 'origin3PA', 'originELP', 'originSeaboardMarine',
            'originFSC', 'originOnTimeForwarding', 'originTFEMAYard', 'originFoxconn',
            'originEscoto', 'originTNCHYard', 'originTNLExpress', 'originTNCH', 'originTNL',
            'originFEMA'
        ], 'applyoriginfilter', 4, 'Origin');

        applyCheckboxFilter([
            'destinationMiami', 'destinationOnTimeForwarding', 'destinationTFEMAYard', 'destinationKN',
            'destinationTNLExpress', 'destinationEscoto', 'destinationTNCHYard', 'destinationKNandN'
        ], 'applydestinationfilter', 6, 'Destination');

        applyCheckboxFilter([
            'currentStatusFinalized',
            'currentStatusInTransit',
            'currentStatusDelivered'
        ], 'applycurrentstatusfilter', 18, 'Current Status');

        applyDateRangeFilter('suggesteddeliverydateFrom', 'suggesteddeliverydateTo', 'applysuggesteddeliverydatefilter', 12, 'Suggested Delivery Date');
        applyDateRangeFilter('prealertFrom', 'prealertTo', 'applyprealertfilter', 7, 'Prealerted Date');
        applyDateRangeFilter('dockdoordateFrom', 'dockdoordateTo', 'applydockdoordatefilter', 19, 'Dock Door Date');
        applyDateRangeFilter('driverassigneddateFrom', 'driverassigneddateTo', 'applydriverassigneddatefilter', 20, 'Driver Assigned Date');
        applyDateRangeFilter('pickupdateFrom', 'pickupdateTo', 'applypickupdatefilter', 21, 'Pick Up Date');
        applyDateRangeFilter('intransitdateFrom', 'intransitdateTo', 'applyintransitdatefilter', 22, 'In Transit Date');
        applyDateRangeFilter('securedyarddateFrom', 'securedyarddateTo', 'applysecuredyarddatefilter', 24, 'Secured Yard Date');
        applyDateRangeFilter('delivereddateFrom', 'delivereddateTo', 'applydelivereddatefilter', 23, 'Delivered Date');
        applyDateRangeFilter('whauthdateFrom', 'whauthdateTo', 'applywhauthdatefilter', 25, 'WH Auth Date');

    // **Limpiar todos los filtros**
const refreshButton = document.getElementById("refreshshipmentstable");
if (refreshButton) {
    refreshButton.addEventListener("click", function () {
        // Limpiar los filtros activos
        activeFilters.length = 0;

        // Mostrar todas las filas de la tabla (resetear la visualización)
        tableRows.forEach(row => row.style.display = "");

        // Limpiar los valores de los inputs de texto
        document.querySelectorAll('input[type="text"]').forEach(input => input.value = "");

        // Limpiar los checkboxes (desmarcarlos)
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);

        // Ocultar el contenedor de filtros activos
        $('#activeFilterDiv').hide();

        console.log("Tabla recargada y filtros resetados");

        // Asegurarse de que los filtros de checkbox también sean aplicados nuevamente si es necesario
        // Llamar a applyFilters para asegurarse de que la tabla se actualice después de borrar los filtros
        applyFilters();
    });
}
});
</script>

<script>
    document.getElementById('exportfile').addEventListener('click', function () {
        var table = document.getElementById('shipmentsTable');
        if (!table) {
            alert("Error: No se encontró la tabla de envíos.");
            return;
        }

        var wb = XLSX.utils.book_new();
        var wsData = [];

        // Encabezados actualizados
        var headers = [
            "Initial Shipment Info", "Shipment Type", "STM ID", "Secondary Shipment ID", "Reference",
            "Origin", "ID Trailer", "Destination", "Pre-Alerted Date & Time", "Carrier Dropping Trailer", "Trailer Owner",
            "Driver & Truck", "Suggested Delivery Date", "Units", "Pallets", "Security Seals", "Notes",
            "Update Shipment Status", "Current Status", "Driver Assigned Date",
            "Pick Up Date", "In Transit Date", "Delivered/Received Date", "Secured Yard Date",
            "Approved ETA Date", "Approved ETA Time",
            "Sec Incident", "Incident Type", "Incident Date", "Incident Notes", "WH Status",
            "At Door Date", "Door Number", "Offloading Time",
            "Date of Billing", "Billing ID",
            "Device Number", "Overhaul ID"
        ];
        wsData.push(headers);

        // Recorrer las filas de la tabla
        let rowCount = 0;
        document.querySelectorAll("#shipmentsTable tbody tr").forEach(row => {
            var cells = row.getElementsByTagName("td");
            if (cells.length === 0) return;

            var rowData = [];

            rowData.push("Initial Shipment Info"); // Initial Shipment Info

            rowData.push(cells[0]?.textContent.trim() || ""); // Shipment Type
            rowData.push(cells[1]?.textContent.trim() || ""); // STM ID
            rowData.push(cells[2]?.textContent.trim() || ""); // Secondary Shipment ID
            rowData.push(cells[3]?.textContent.trim() || ""); // Reference
            rowData.push(cells[4]?.textContent.trim() || ""); // Origin
            rowData.push(cells[5]?.textContent.trim() || ""); // ID Trailer
            rowData.push(cells[6]?.textContent.trim() || ""); // Destination

            // Formatear Pre-Alerted DateTime a MM/DD/YYYY hh:mm:ss AM/PM
                let preAlertDateTime = cells[7]?.textContent.trim() || "";
                if (preAlertDateTime) {
                    let dateObj = new Date(preAlertDateTime);
                    let month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
                    let day = dateObj.getDate().toString().padStart(2, '0');
                    let year = dateObj.getFullYear();
                    let hours = dateObj.getHours();
                    let minutes = dateObj.getMinutes().toString().padStart(2, '0');
                    let seconds = dateObj.getSeconds().toString().padStart(2, '0');
                    let ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12 || 12;
                    rowData.push(`${month}/${day}/${year} ${hours}:${minutes}:${seconds} ${ampm}`);
                } else {
                    rowData.push("");
                }


            rowData.push(cells[8]?.textContent.trim() || ""); // Carrier Dropping Trailer
            rowData.push(cells[9]?.textContent.trim() || ""); // Trailer Owner

            rowData.push(`${cells[10]?.textContent.trim() || ""} - ${cells[11]?.textContent.trim() || ""}`); // Driver & Truck

            rowData.push(cells[12]?.textContent.trim() || ""); // Suggested Delivery Date (antes ETD)
            rowData.push(cells[13]?.textContent.trim() || ""); // Units
            rowData.push(cells[14]?.textContent.trim() || ""); // Pallets

            rowData.push(`${cells[15]?.textContent.trim() || ""} - ${cells[16]?.textContent.trim() || ""}`); // Security Seals

            rowData.push(cells[17]?.textContent.trim() || ""); // Notes

            rowData.push("Update Shipment Status"); // Update Shipment Status

            rowData.push(cells[18]?.textContent.trim() || ""); // Current Status
            rowData.push(cells[20]?.textContent.trim() || ""); // Driver Assigned Date
            rowData.push(cells[21]?.textContent.trim() || ""); // Pick Up Date
            rowData.push(cells[22]?.textContent.trim() || ""); // In Transit Date
            rowData.push(cells[23]?.textContent.trim() || ""); // Delivered Date
            rowData.push(cells[24]?.textContent.trim() || ""); // Secured Yard Date

            // Separar "WH Auth Date" en "Approved ETA Date" y "Approved ETA Time"
            let whAuthDateTime = cells[25]?.textContent.trim() || "";
            if (whAuthDateTime) {
                let [date, time] = whAuthDateTime.split(" ");
                rowData.push(date || ""); // Approved ETA Date
                rowData.push(time || ""); // Approved ETA Time
            } else {
                rowData.push("", ""); // Si está vacío, poner celdas vacías
            }

            rowData.push(cells[26]?.textContent.trim() || ""); // Sec Incident
            rowData.push(cells[27]?.textContent.trim() || ""); // Incident Type
            rowData.push(cells[28]?.textContent.trim() || ""); // Incident Date
            rowData.push(cells[29]?.textContent.trim() || ""); // Incident Notes
            rowData.push(cells[30]?.textContent.trim() || ""); // WH Status
            rowData.push(cells[31]?.textContent.trim() || ""); // At Door Date
            rowData.push(cells[32]?.textContent.trim() || ""); // Door Number
            rowData.push(cells[33]?.textContent.trim() || ""); // Offloading Time

            rowData.push("", ""); // Columnas vacías para "Date of Billing" y "Billing ID"

            rowData.push(`${cells[34]?.textContent.trim() || ""} - ${cells[35]?.textContent.trim() || ""} - ${cells[36]?.textContent.trim() || ""}`); // Device Number

            rowData.push(cells[37]?.textContent.trim() || ""); // Overhaul ID (security_company_ID)

            wsData.push(rowData);
            rowCount++;
        });

        if (rowCount === 0) {
            alert("Error: No hay datos en la tabla para exportar.");
            return;
        }

        // Crear la hoja de cálculo
        var ws = XLSX.utils.aoa_to_sheet(wsData);
        XLSX.utils.book_append_sheet(wb, ws, "Shipments");

        // Generar nombre de archivo con timestamp
        var now = new Date();
        var formattedDateTime = now.toISOString().replace(/T/, '_').replace(/:/g, '-').split('.')[0];
        var filename = `Shipments_${formattedDateTime}.xlsx`;

        // Exportar el archivo Excel
        XLSX.writeFile(wb, filename);
    });
</script>

<script>
    function checkAndChangeStatus(dateFieldId, statusDescription, shipmentId) {
        const dateField = document.getElementById(dateFieldId);
        const currentDateValue = dateField.value;

        // Verificar si ya existe una fecha en el campo y evitar el cambio de estado
        if (currentDateValue) {
            console.log(`El campo ${dateFieldId} ya tiene una fecha, no se cambiará el estado.`);
            return; // Si ya hay una fecha, no cambiar el estado
        }

        // Cambiar el estado solo si el campo está vacío, usando la descripción
        changeStatusByDescription(statusDescription, shipmentId);
    }

    function checkAndChangeStatusForSelect(selectFieldId, statusDescription, shipmentId) {
    const selectField = document.getElementById(selectFieldId);

    // Cambiar el estado inmediatamente al hacer clic
    changeStatusByDescription(statusDescription, shipmentId);
    }

    // Cambiar el estado utilizando la descripción
    function changeStatusByDescription(statusDescription, shipmentId) {
        console.log('shipmentId recibido:', shipmentId); // Verifica el valor de shipmentId
        console.log('Estado recibido:', statusDescription); // Verifica la descripción del estado

        // Aquí es donde mapeamos la descripción al valor correspondiente de gntc_description
        const statusMapping = {
            'Picked Up': 'Picked Up',       // gntc_description 'Picked Up'
            'Driver Assigned': 'Driver Assigned', // gntc_description 'Driver Assigned'
            'In Transit': 'In Transit',     // gntc_description 'In Transit'
            'Secured Yard': 'Secured Yard',
            'Dock Door': 'Dock Door',
            // Agrega otras descripciones si es necesario
        };

        const gntcDescription = statusMapping[statusDescription];

        if (gntcDescription) {
            const statusSelect = document.getElementById('currentStatus-' + shipmentId);
            if (statusSelect) {
                // Buscar el option que tenga el gntc_description correspondiente
                const options = statusSelect.getElementsByTagName('option');
                for (let i = 0; i < options.length; i++) {
                    if (options[i].textContent.trim() === gntcDescription) {
                        statusSelect.value = options[i].value; // Establecer el valor del select según el texto de la opción
                        console.log('Estado cambiado a:', gntcDescription);
                        break;
                    }
                }
            } else {
                console.error('No se encontró el select para el envío:', shipmentId);
            }
        } else {
            console.error('Descripción de estado no mapeada:', statusDescription);
        }
    }
</script>
<script>
    flatpickr('.flatpickr', {
        dateFormat: 'm/d/Y H:i', // Define el formato que deseas mostrar
        enableTime: true, // Habilita la selección de hora
        time_24hr: true, // Si deseas que la hora sea en formato de 24 horas
        onOpen: function (selectedDates, dateStr, instance) {
            // Si el campo está vacío, se coloca la fecha y hora actual
            if (dateStr === "") {
                instance.setDate(new Date(), true); // Establece la fecha actual
            }
        },
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Seleccionamos todos los modales que tienen el prefijo 'shipmentModal' en su ID
        const shipmentModals = document.querySelectorAll("[id^='shipmentModal']");

        shipmentModals.forEach(shipmentModal => {
            // Al abrir el modal, guardar los valores actuales de los campos de fecha y otros
            shipmentModal.addEventListener("shown.bs.modal", function () {
                const dateInputs = shipmentModal.querySelectorAll("input[type='date'], input[type='datetime-local'], .flatpickr");
                const selectInputs = shipmentModal.querySelectorAll("select");

                dateInputs.forEach(input => {
                    if (input.value) {
                        input.dataset.original = input.value; // Guardar el valor en el dataset original
                    } else {
                        input.dataset.original = ''; // Si está vacío, también guardamos ese estado
                    }
                });

                selectInputs.forEach(select => {
                    if (select.value) {
                        select.dataset.original = select.value; // Guardar el valor en el dataset original
                    } else {
                        select.dataset.original = ''; // Si está vacío, también guardamos ese estado
                    }
                });
            });

            // Al cerrar el modal, restaurar los valores originales
            shipmentModal.addEventListener("hidden.bs.modal", function () {
                const inputs = shipmentModal.querySelectorAll("input, select, textarea, .flatpickr");
                inputs.forEach(input => {
                    if (input.dataset.original !== undefined) {
                        input.value = input.dataset.original; // Restaurar el valor original
                    }
                });
            });
        });
    });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[id^='nextButton-']").forEach(button => {
        button.addEventListener("click", function () {
            // Obtener el ID del shipment
            var shipmentId = button.id.replace("nextButton-", "");

            // Buscar la pestaña de destino
            var nextTab = document.querySelector("#pills-update-status-tab" + shipmentId);

            if (nextTab) {
                console.log("Cambiando a la pestaña:", nextTab.id); // Debug
                var tab = new bootstrap.Tab(nextTab);
                tab.show();

                // Esperar a que la pestaña se muestre antes de hacer scroll
                setTimeout(() => {
                    document.querySelector("#pills-update-status-" + shipmentId).scrollIntoView({ behavior: "smooth" });
                }, 100);
            } else {
                console.error("No se encontró la pestaña de destino.");
            }
        });
    });
});
</script>

<script>
 // JavaScript para cambiar de pestaña al hacer clic en "Next"
 document.getElementById('nextButton').addEventListener('click', function () {
    // Obtener el STM ID dinámicamente
    var stmId = '{{ $shipment->stm_id ?? '' }}';

    // Obtener la siguiente pestaña basada en el STM ID
    var nextTab = document.getElementById('pills-update-status-tab' + stmId);

    if (nextTab) {
        // Cambiar a la siguiente pestaña usando Bootstrap
        var tab = new bootstrap.Tab(nextTab);
        tab.show(); // Muestra la siguiente pestaña
    }
});
</script>


<script>
    function validateShipment(stm_id) {
        const unitsInput = document.getElementById('units-' + stm_id);
        const palletsInput = document.getElementById('pallets-' + stm_id);
        const errorMessage = document.getElementById('error-message-' + stm_id);

        const units = parseInt(unitsInput.value.trim());
        const pallets = parseInt(palletsInput.value.trim());

        if (isNaN(units) || units <= 0) {
            errorMessage.textContent = "Units cannot be empty or 0.";
            errorMessage.style.display = "block";
            unitsInput.classList.add('is-invalid');
            return false;
        } else {
            unitsInput.classList.remove('is-invalid');
        }

        if (isNaN(pallets) || pallets <= 0) {
            errorMessage.textContent = "Pallets cannot be empty or 0.";
            errorMessage.style.display = "block";
            palletsInput.classList.add('is-invalid');
            return false;
        } else {
            palletsInput.classList.remove('is-invalid');
        }

        if (pallets > units) {
            errorMessage.textContent = "Pallets cannot be greater than Units.";
            errorMessage.style.display = "block";
            palletsInput.classList.add('is-invalid');
            return false;
        } else {
            palletsInput.classList.remove('is-invalid');
        }

        errorMessage.style.display = "none";
        return true;
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Al cargar la página, deshabilitar los campos con valores existentes, pero sin perder sus valores
        document.querySelectorAll(".flatpickr").forEach(function (input) {
            if (input.value.trim() !== "") {
                // Crear un campo oculto para almacenar el valor original
                let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = input.name;
                hiddenInput.value = input.value.trim();
                input.parentElement.appendChild(hiddenInput); // Añadir el campo oculto al formulario

                input.setAttribute("disabled", "disabled"); // Deshabilitar el campo
            }
        });

        // Cuando el usuario cambia un campo, removemos la deshabilitación
        document.querySelectorAll(".flatpickr").forEach(function (input) {
            input.addEventListener("input", function () {
                if (input.value.trim() !== "") {
                    input.removeAttribute("disabled"); // Habilitar el campo si tiene valor
                }
            });
        });

        // Al hacer clic en "guardar", deshabilitar campos que tienen valor y no permitir cambios
        document.getElementById("saveButton").addEventListener("click", function () {
            document.querySelectorAll(".flatpickr").forEach(function (input) {
                if (input.value.trim() !== "") {
                    input.setAttribute("disabled", "disabled"); // Deshabilitar el campo
                }
            });
        });
    });

    // Evitar que los valores de los campos deshabilitados se pierdan al hacer submit
    document.querySelector("form").addEventListener("submit", function (event) {
        document.querySelectorAll(".flatpickr").forEach(function (input) {
            if (input.disabled) {
                let hiddenInput = document.querySelector(`input[name="${input.name}"]`);
                if (hiddenInput) {
                    hiddenInput.value = input.value; // Mantener el valor original al hacer submit
                }
            }
        });
    });
    </script>

@endsection

@section('custom-css')
<style>

  /* Cambiar fondo y texto */

/* Estilo para las pestañas */
 /* Estilo para las pestañas nav-pills */
 .nav-tabs {
    font-weight: 600;
    background-color: #f8f9fa;
    color: #007bff;
    border: 1px solid #ddd;
    border-radius: 50px;
    transition: background-color 0.3s, color 0.3s;
    display: flex;
    justify-content: center;
    flex-wrap: wrap; /* Esto permite que las pestañas se ajusten en pantallas más pequeñas */
}

/* Pestañas inactivas con texto oscuro */
.nav-pills .nav-link {
    font-weight: 600;
    background-color: #f8f9fa;
    color: #007bff;
    border: 1px solid #ddd;
    border-radius: 50px;
    margin: 0 10px 10px 10px; /* Separación horizontal y vertical */
    padding: 10px 15px;
    transition: background-color 0.3s, color 0.3s;
}

/* Estilo para la pestaña activa */
.nav-pills .nav-link.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

/* Estilo para la pestaña cuando se pasa el cursor */
.nav-pills .nav-link:hover {
    background-color: #e2e6ea;
    color: #0056b3;
}

/* Media query para pantallas móviles */
@media (max-width: 576px) {
    .nav-tabs {
        flex-direction: column; /* Hace que las pestañas se alineen verticalmente */
        align-items: center; /* Centra las pestañas en la pantalla */
    }

    .nav-pills .nav-link {
        margin: 5px 0; /* Espaciado vertical en pantallas pequeñas */
    }
}

    /* Estilo para el modal */
    .modal-header {
        background-color: #007bff;
        color: white;
    }

    .modal-title {
        font-weight: 600;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #5a6268;
    }

    /* Estilo de contenido dentro de las pestañas */
    .tab-content p {
        font-size: 14px;
        line-height: 1.6;
    }
</style>

