<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Support\Facades\Auth;
use App\Models\GenericCatalog;
use App\Models\Shipments;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function calendarshow()
    {
        if (Auth::check()) {
            // Obtener el ID del estado 'Finalized' desde el catálogo
            $finalizedStatus = GenericCatalog::where('gntc_value', 'Finalized')
                ->where('gntc_group', 'STATUS_E_REPORT')
                ->first();

            // Filtrar los envíos que no tienen el estado 'Finalized'
            $shipments = Shipments::where('gnct_id_current_status', '!=', $finalizedStatus->gnct_id)->get();
            $currentStatus = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get();

            // Obtener los catálogos para 'MWD_LOCATION' (origen) y 'STATUS_E_REPORT' (estado actual)
            $originCatalog = GenericCatalog::where('gntc_group', 'MWD_LOCATION')->get()->keyBy('gnct_id');
            $statusCatalog = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get()->keyBy('gnct_id');

            // Obtener el primer envío, o dejar $shipment como null si no hay envíos
            $shipment = $shipments->first() ?? null;

            // Mapear los datos al formato esperado por FullCalendar
            $events = $shipments->map(function ($shipment) use ($originCatalog, $statusCatalog) {
                // Parsear fechas relevantes con Carbon
                $etd = Carbon::parse($shipment->etd);  // Cambiado de suggestedDeliveryDate a etd
                $whAuthDate = $shipment->wh_auth_date ? Carbon::parse($shipment->wh_auth_date) : null;

                // Obtener las descripciones para 'origin' y 'gnct_id_current_status'
                $originDescription = isset($originCatalog[$shipment->origin])
                    ? $originCatalog[$shipment->origin]->gntc_value
                    : 'Unknown';

                $statusDescription = isset($statusCatalog[$shipment->gnct_id_current_status])
                    ? $statusCatalog[$shipment->gnct_id_current_status]->gntc_value
                    : 'Unknown';

                return [
                    'title' => 'STM ID: ' . $shipment->stm_id,
                    'start' => $etd->format('Y-m-d\TH:i:s'),
                    'end' => $etd->addHours(1)->format('Y-m-d\TH:i:s'),
                    'extendedProps' => [
                        'stm_id' => $shipment->stm_id,
                        'reference' => $shipment->reference,
                        'origin' => $originDescription,
                        'destination' => $shipment->destination,
                        'current_status' => $statusDescription,
                        'etd' => $etd->format('m/d/Y H:i'),  // Cambiado de suggested_delivery_date a etd
                        'wh_auth_date' => $whAuthDate ? $whAuthDate->format('m/d/Y H:i') : 'N/A',
                        'units' => $shipment->units,
                        'pallets' => $shipment->pallets,
                        'id_trailer' => $shipment->id_trailer,
                    ],
                ];
            });

            // Retornar la vista con los datos necesarios
            return view('home.calendar', [
                'events' => $events->toArray(),
                'shipments' => $shipments,
                'originCatalog' => $originCatalog,
                'statusCatalog' => $statusCatalog,
                'shipment' => $shipment,
                'currentStatus'=> $currentStatus,
            ]);
        }

        return redirect('/login');
    }

    public function historicalcalendarshow()
    {
        if (Auth::check()) {
            // Obtener todos los envíos
            $shipments = Shipments::all();
            $currentStatus = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get();

            // Obtener los catálogos para 'MWD_LOCATION' (origen) y 'STATUS_E_REPORT' (estado actual)
            $originCatalog = GenericCatalog::where('gntc_group', 'MWD_LOCATION')->get()->keyBy('gnct_id');
            $statusCatalog = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get()->keyBy('gnct_id');

            // Obtener el primer envío, o dejar $shipment como null si no hay envíos
            $shipment = $shipments->first() ?? null;

            // Mapear los datos al formato esperado por FullCalendar
            $events = $shipments->map(function ($shipment) use ($originCatalog, $statusCatalog) {
                // Parsear fechas relevantes con Carbon
                $etd = Carbon::parse($shipment->etd); // Cambiado de suggestedDeliveryDate a etd
                $whAuthDate = $shipment->wh_auth_date ? Carbon::parse($shipment->wh_auth_date) : null;

                // Obtener las descripciones para 'origin' y 'gnct_id_current_status'
                $originDescription = $originCatalog[$shipment->origin]->gntc_value ?? 'Unknown';
                $statusDescription = $statusCatalog[$shipment->gnct_id_current_status]->gntc_value ?? 'Unknown';

                return [
                    'title' => 'STM ID: ' . $shipment->stm_id,
                    'start' => $etd->format('Y-m-d\TH:i:s'),
                    'end' => $etd->addHours(1)->format('Y-m-d\TH:i:s'),
                    'extendedProps' => [
                        'stm_id' => $shipment->stm_id,
                        'reference' => $shipment->reference,
                        'origin' => $originDescription,
                        'destination' => $shipment->destination,
                        'current_status' => $statusDescription,
                        'etd' => $etd->format('m/d/Y H:i'), // Cambiado de suggested_delivery_date a etd
                        'wh_auth_date' => $whAuthDate ? $whAuthDate->format('m/d/Y H:i') : 'N/A',
                        'units' => $shipment->units,
                        'pallets' => $shipment->pallets,
                        'id_trailer' => $shipment->id_trailer,
                    ],
                ];
            });

            // Retornar la vista con los datos necesarios
            return view('home.historicalcalendar', [
                'events' => $events->toArray(),
                'shipments' => $shipments,
                'originCatalog' => $originCatalog,
                'statusCatalog' => $statusCatalog,
                'shipment' => $shipment,
                'currentStatus' => $currentStatus,
            ]);
        }

        return redirect('/login');
    }

    public function getShipmentDetails($pk_shipment)
    {
        // Buscar el envío por la clave primaria
        $shipment = Shipments::with(['currentStatus', 'originCatalog'])->findOrFail($pk_shipment);

        // Formatear los datos para enviarlos al frontend
        return response()->json([
            'trailer_id' => $shipment->id_trailer,
            'stm_id' => $shipment->stm_id,
            'current_status' => $shipment->currentStatus ? $shipment->currentStatus->gntc_value : 'Unknown',
            'delivered_date' => $shipment->formatted_delivered_date,
            'at_door_date' => $shipment->at_door_date ? $shipment->at_door_date->format('m/d/Y H:i') : 'N/A',
            'offloading_time' => $shipment->offloading_time ?? 'N/A', // Cambio aquí para tipo Time
            'wh_auth_date' => $shipment->wh_auth_date ? $shipment->wh_auth_date->format('m/d/Y H:i') : 'N/A',
        ]);
    }

    public function updateOffloadingStatus(Request $request, $pk_shipment)
    {
        try {
            // Buscar el envío por pk_shipment
            $shipment = Shipments::findOrFail($pk_shipment);


            // Validar los datos recibidos
                $validatedData = $request->validate([
                    'trailer_id' => 'nullable|string|max:255', // ID del remolque
                    'stm_id' => 'nullable|integer', // ID del STM
                    'gnct_id_current_status' => 'nullable|integer', // Estado actual
                    'delivered_date' => 'nullable|date', // Fecha de entrega
                    'at_door_date' => 'nullable|date', // Fecha de llegada
                    'offloading_time' => 'nullable|date_format:H:i', // Hora de descarga
                    'wh_auth_date' => 'nullable|date', // Fecha de autorización
                ]);

            // Actualizar el envío con los nuevos datos
            $shipment->update($validatedData);


            // Devolver respuesta con el estado de la actualización
            return response()->json(['message' => 'Shipment updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating shipment: ' . $e->getMessage()], 500);
        }
    }
}
