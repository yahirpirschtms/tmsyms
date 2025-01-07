<?php

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\EmptyTrailerController;
use App\Http\Controllers\GenericCatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'show']);

Route::post('/login', [LoginController::class, 'login']);

Route::get('/home', [HomeController::class, 'index']);

Route::post('/emptytrailer/store', [HomeController::class, 'store'])->name('emptytrailer.store');

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

//Ruta validacion formulario Empty Trailer
Route::post('/emptytrailer/validate', [HomeController::class, 'validateField'])->name('emptytrailer.validate');

//Eliminar el empty trailer
Route::delete('/empty-trailer/{id}', [HomeController::class, 'destroy'])->name('emptytrailer.destroy');



