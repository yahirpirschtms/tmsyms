<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function calendarshow()
    {
    if (Auth::check()) {
        $shipments = Shipment::all();  // Obtén los envíos desde la base de datos
          // Esto te ayudará a verificar si los envíos se están obteniendo correctamente
        return view('home.calendar', compact('shipments'));  // Cambia aquí el nombre de la vista
    }
    return redirect('/login');
    }
    public function index()
    {
        // Obtenemos los datos de la base de datos
        $shipments = Shipment::all();

        // Mapeamos los datos al formato esperado por FullCalendar
        $events = $shipments->map(function ($shipment) {
            // Convertimos la fecha sugerida de entrega a un objeto Carbon
            $suggestedDeliveryDate = Carbon::parse($shipment->suggesteddeliverydate);

            return [
                'title' => 'STM ID: ' . $shipment->stm_id,
                'start' => $suggestedDeliveryDate->format('m-d-y\TH:i:s'), // Formato ISO 8601
                'description' => "
                    Reference: {$shipment->reference},
                    Origin: {$shipment->origin},
                    Destination: {$shipment->destination},
                    Units: {$shipment->units},
                    Pallets: {$shipment->pallets},
                    Suggested Delivery Date: {$suggestedDeliveryDate->format('d/m/Y H:i')}
                ",
            ];
        });

        // Pasamos los eventos a la vista
        return view('calendar', ['events' => $events->toArray()]);
    }
}
