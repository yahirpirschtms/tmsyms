<?php

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\EmptyTrailerController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\GenericCatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'show']);

Route::post('/login', [LoginController::class, 'login']);

//Route::get('/home', [HomeController::class, 'index']);

//Ruta guardar un nuveo Empty Trailer
Route::post('/emptytrailer/store', [HomeController::class, 'store'])->name('emptytrailer.store');

//Ruta guardar un nuevo Shipment
Route::post('/shipment/store', [ShipmentController::class, 'store'])->name('shipment.store');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/trafficworkflowstart', [ShipmentController::class, 'workflowtrafficstartshow'])->name('workflowtrafficstart');

Route::get('/home', [HomeController::class, 'index'])->name('emptytrailer');

Route::get('/shipments', [ShipmentController::class, 'allshipmentsshow'])->name('all-shipments');

//Rutas nutrir selects para la pantalla de empty trailer
Route::get('/availability-indicators', [GenericCatalogController::class, 'getAvailabilityIndicators'])->name('availabilityindicators-emptytrailer');

Route::get('/locations-emptytrailer', [CompaniesController::class, 'getLocations'])->name('locations-emptytrailer');

Route::get('/carrier-emptytrailer', [CompaniesController::class, 'getLocations'])->name('carrier-emptytrailer');

//Ruta actualizacion de Tabla EmptyTrailer con boton refresh o automaticamente
Route::get('/emptytrailer/data', [HomeController::class, 'getEmptyTrailers'])->name('emptytrailer.data');

//Ruta actualizacion de Tabla ShipmentsWH con boton refresh o automaticamente
Route::get('/shipmentwh/data', [ShipmentController::class, 'getShipmentswh'])->name('shipmentwh.data');

//Eliminar el empty trailer
Route::delete('/trailers/{id}', [HomeController::class, 'destroy'])->name('trailers.destroy');

//Ruta para mostrar los detalles de un Empty trailer en el offcanvas
Route::get('/getTrailerDetails/{id}', [HomeController::class, 'getTrailerDetails']);

//Actualizar empty trailer
Route::put('/emptytrailer/update', [HomeController::class, 'update'])->name('emptytrailer.update');

// Ruta para crear el flujo de "workflow start" con un empty trailer
Route::get('/createworkflowstartwithemptytrailer', [ShipmentController::class, 'createWorkflowStartWithEmptyTrailer'])->name('createworkflowstartwithemptytrailer');

//Rutas nutrir selects para la pantalla de Shipments
Route::get('/shipment-types', [GenericCatalogController::class, 'getShipmentTypes'])->name('shipmenttypes-shipment');

//Rutas nutrir selects para la pantalla de Shipments con CurrentStatus
Route::get('/current-status', [GenericCatalogController::class, 'getCurrentStatus'])->name('currentstatus-shipment');

//Rutas nutrir selects para la pantalla de Shipments con STM ID
Route::get('/services', [ServicesController::class, 'getServices'])->name('services-shipment');

//Rutas nutrir selects para la pantalla de Shipments con los destinations
Route::get('/destinations-shipments', [FacilitiesController::class, 'getFacilities'])->name('destinations-shipments');

//Rutas nutrir selects para la pantalla de Shipments con los Drivers
Route::get('/drivers-shipments/{id_company}', [DriversController::class, 'getDriversByCompany'])->name('drivers-shipments');

//Ruta ir a WH ETA Approval sin filtros
Route::get('/whapptapproval', [ShipmentController::class, 'indexwhapptapproval'])->name('whapptapproval');

//Ruta Asignar WH ETA Approval a Shipments
Route::put('/shipment/whetaapproval', [ShipmentController::class, 'whetaapproval'])->name('shipment.whetaapproval');



