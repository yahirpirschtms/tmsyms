<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\EmptyTrailerController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\GenericCatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'show']);

Route::post('/login', [LoginController::class, 'login'])->name('login');

//Route::get('/home', [HomeController::class, 'index']);

//Middleware para usuarios no autentificados
Route::middleware('auth')->group(function () {

//Ruta guardar un nuveo Empty Trailer
Route::post('/emptytrailer/store', [HomeController::class, 'store'])->name('emptytrailer.store');

//Ruta guardar un nuevo Shipment
Route::post('/shipment/store', [ShipmentController::class, 'store'])->name('shipment.store');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/trafficworkflowstart', [ShipmentController::class, 'workflowtrafficstartshow'])->name('workflowtrafficstart');

Route::get('/home', [HomeController::class, 'index'])->name('emptytrailer');

//Ruta para ir a el dashboard
Route::get('/dashboard', [DashboardController::class, 'getdashboard'])->name('dashboard');

//Ruta para actualizacion del dashboard
Route::get('/updatedashboard', [DashboardController::class, 'getupdatedashboard'])->name('updatedashboard');

Route::get('/shipments', [ShipmentController::class, 'allshipmentsshow'])->name('all-shipments');

Route::get('/get-service', [ShipmentController::class, 'getService'])->name('information-shipment');

Route::get('/get-lanestrafficworkflowstart', [ShipmentController::class, 'getLanesTrafficWorkflowStart']);

//Rutas nutrir selects para la pantalla de empty trailer
Route::get('/availability-indicators', [GenericCatalogController::class, 'getAvailabilityIndicators'])->name('availabilityindicators-emptytrailer');

Route::get('/locations-emptytrailer', [CompaniesController::class, 'getLocations'])->name('locations-emptytrailer');

Route::get('/carrier-emptytrailer', [CompaniesController::class, 'getLocations'])->name('carrier-emptytrailer');

//peticiones de los selects con ajax y poder insertar
Route::get('/locations-emptytrailerAjax', [CompaniesController::class, 'getLocationsAjax'])->name('locations-emptytrailerAjax');

Route::get('/carrier-emptytrailerAjax', [CompaniesController::class, 'getCarriersAjax'])->name('carrier-emptytrailerAjax');

Route::get('/trailerowner-emptytrailerAjax', [CompaniesController::class, 'getTrailerOwnersAjax'])->name('trailerowner-emptytrailerAjax');

Route::get('/doornumberwheta-whetaapproval', [GenericCatalogController::class, 'getDoorNumberWHETAAjax'])->name('doornumberwheta-whetaapproval');

Route::get('/drivers-shipment', [DriversController::class, 'getDriversAjax'])->name('drivers-shipment');

Route::get('/securitycompany-shipment', [GenericCatalogController::class, 'getSecurityCompaniesAjax'])->name('securitycompany-shipment');

//Agregar un nuevo carrier en la pantalla empty trailer
Route::post('/save-new-carrier', [CompaniesController::class, 'saveNewCarrier']);

//Agregar un nuevo location en la pantalla empty trailer
Route::post('/save-new-location', [CompaniesController::class, 'saveNewLocation']);

//Agregar un nuevo trailer owner en la pantalla empty trailer
Route::post('/save-new-trailerowner', [CompaniesController::class, 'saveNewTrailerOwner']);

//Agregar un nuevo driver en la pantalla empty trailer
Route::post('/save-new-driver', [DriversController::class, 'saveNewDriver']);

//Agregar un nuevo door Number en la pantalla WH ETA Approval
Route::post('/save-new-doornumberwheta', [GenericCatalogController::class, 'saveNewDoorNumberWHETA']);

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
//Route::get('/destinations-shipments', [FacilitiesController::class, 'getFacilities'])->name('destinations-shipments');
Route::get('/destinations-shipments', [CompaniesController::class, 'getDestinationsAjax'])->name('destinations-shipments');

//Rutas nutrir selects para la pantalla de Shipments con los Drivers
Route::get('/drivers-shipments/{id_company}', [DriversController::class, 'getDriversByCompany'])->name('drivers-shipments');

//Ruta ir a WH ETA Approval sin filtros
Route::get('/whapptapproval', [ShipmentController::class, 'indexwhapptapproval'])->name('whapptapproval');

//Ruta Asignar WH ETA Approval a Shipments
Route::put('/shipment/whetaapproval', [ShipmentController::class, 'whetaapproval'])->name('shipment.whetaapproval');

//Rutas para el calendario
Route::get('/shipment/{pk_shipment}', [CalendarController::class, 'getShipmentDetails'])->name('shipment.details');

Route::get('/calendar', [CalendarController::class, 'calendarshow'])->name('calendar.view');

Route::get('/historicalcalendar', [CalendarController::class, 'historicalcalendarshow'])->name('historicalcalendar.view');

//Rutas Christian
Route::get('/liveshipments', [ShipmentController::class, 'liveshipmentsshow'])->name('liveshipments');
Route::get('/all-shipments', [ShipmentController::class, 'allshipmentsshow'])->name('all-shipments');



Route::get('/shipments/details/{pk_shipment}', [ShipmentController::class, 'details'])->name('shipments.details');
Route::put('/shipments/{id}', [ShipmentController::class, 'update'])->name('shipments.update');
Route::put('/shipments/{shipment}/updateNotes', [ShipmentController::class, 'updateNotes'])->name('shipments.updateNotes');
Route::put('/update-status-endpoint/{pk_shipment}', [ShipmentController::class, 'update'])->name('update.status');
Route::get('/shipment/status', [GenericCatalogController::class, 'getOrigin']);

Route::get('/shipment/{pk_shipment}', [CalendarController::class, 'getShipmentDetails'])->name('shipment.details');
Route::put('/update-status/{pk_shipment}', [CalendarController::class, 'updateOffloadingStatus'])->name('update.status');
Route::post('/get-status-id', [ShipmentController::class, 'getStatusIdByDescription']);

Route::get('/path/to/previous-shipment/{pk_shipment}', [ShipmentController::class, 'getPreviousShipment'])->name('shipment.previous');
});