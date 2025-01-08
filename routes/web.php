<?php

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

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/trafficworkflowstart', [ShipmentController::class, 'workflowtrafficstartshow'])->name('workflowtrafficstart');

Route::get('/liveshipments', [ShipmentController::class, 'liveshipmentsshow'])->name('liveshipments');

Route::get('/home', [HomeController::class, 'index'])->name('emptytrailer');

Route::get('/all-shipments', [ShipmentController::class, 'allshipmentsshow'])->name('all-shipments');

Route::get('/availability-indicators', [GenericCatalogController::class, 'getAvailabilityIndicators'])->name('availability.indicators');


Route::get('/shipments/details/{pk_shipment}', [ShipmentController::class, 'details'])->name('shipments.details');
Route::put('/shipments/{id}', [ShipmentController::class, 'update'])->name('shipments.update');
Route::put('/shipments/{shipment}/updateNotes', [ShipmentController::class, 'updateNotes'])->name('shipments.updateNotes');
Route::put('/update-status-endpoint/{pk_shipment}', [ShipmentController::class, 'update'])->name('update.status');
