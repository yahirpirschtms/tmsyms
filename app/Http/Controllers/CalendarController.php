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
                ->where('gntc_group', 'current_status')
                ->first();

            // Filtrar los envíos que no tienen el estado 'Finalized'
            $shipments = Shipments::where('gnct_id_current_status', '!=', $finalizedStatus->gnct_id)->get();
            $currentStatus = GenericCatalog::where('gntc_group', 'current_status')->get();

            // Obtener los catálogos para 'MWD_LOCATION' (origen) y 'STATUS_E_REPORT' (estado actual)
            $originCatalog = GenericCatalog::where('gntc_group', 'MWD_LOCATION')->get()->keyBy('gnct_id');
            $statusCatalog = GenericCatalog::where('gntc_group', 'current_status')->get()->keyBy('gnct_id');

            // Obtener el primer envío, o dejar $shipment como null si no hay envíos
            $shipment = $shipments->first() ?? null;

            // Mapear los datos al formato esperado por FullCalendar
            $events = $shipments->map(function ($shipment) use ($originCatalog, $statusCatalog) {
                // Parsear fechas relevantes con Carbon
                $etd = Carbon::parse($shipment->etd);  // Cambiado de suggestedDeliveryDate a etd
                $whAuthDate = $shipment->wh_auth_date ? Carbon::parse($shipment->wh_auth_date) : null;

                // Si no hay fecha de autorización de almacén, no se incluirá el evento
                if ($whAuthDate === null) {
                    return null;  // Si no tiene fecha de autorización, no se incluye
                }

                // Obtener las descripciones para 'origin' y 'gnct_id_current_status'
                $originDescription = isset($originCatalog[$shipment->origin])
                    ? $originCatalog[$shipment->origin]->gntc_value
                    : 'Unknown';

                $statusDescription = isset($statusCatalog[$shipment->gnct_id_current_status])
                    ? $statusCatalog[$shipment->gnct_id_current_status]->gntc_value
                    : 'Unknown';

                return [
                    'title' => 'STM ID: ' . $shipment->stm_id,
                    'start' => $whAuthDate ? $whAuthDate->format('Y-m-d\TH:i:s') : null,  // Comprobamos si whAuthDate no es nulo
                    'end' => $whAuthDate ? $whAuthDate->addHours(1)->format('Y-m-d\TH:i:s') : null,
                    'extendedProps' => [
                        'stm_id' => $shipment->stm_id,
                        'reference' => $shipment->reference,
                        'origin' => $originDescription,
                        'destination' => $shipment->destination,
                        'current_status' => $statusDescription,
                        'etd' => $etd->format('m/d/Y H:i'),
                        'wh_auth_date' => $whAuthDate ? $whAuthDate->format('m/d/Y H:i') : 'Not Available',
                        'units' => $shipment->units,
                        'pallets' => $shipment->pallets,
                        'id_trailer' => $shipment->id_trailer,
                    ],
                ];
            });

            // Filtrar eventos nulos
            $events = $events->filter(function ($event) {
                return $event !== null;  // Asegurarse de que solo los eventos con fecha válida de autorización sean incluidos
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
            $currentStatus = GenericCatalog::where('gntc_group', 'current_status')->get();

            // Obtener los catálogos para 'MWD_LOCATION' (origen) y 'STATUS_E_REPORT' (estado actual)
            $originCatalog = GenericCatalog::where('gntc_group', 'MWD_LOCATION')->get()->keyBy('gnct_id');
            $statusCatalog = GenericCatalog::where('gntc_group', 'current_status')->get()->keyBy('gnct_id');

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
                    'start' => $whAuthDate ? $whAuthDate->format('Y-m-d\TH:i:s') : null,  // Comprobamos si whAuthDate no es nulo
                    'end' => $whAuthDate ? $whAuthDate->addHours(1)->format('Y-m-d\TH:i:s') : null,  // Comprobamos si whAuthDate no es nulo antes de añadir hora
                    'extendedProps' => [
                        'stm_id' => $shipment->stm_id,
                        'reference' => $shipment->reference,
                        'origin' => $originDescription,
                        'destination' => $shipment->destination,
                        'current_status' => $statusDescription,
                        'etd' => $etd->format('m/d/Y H:i'),
                        'wh_auth_date' => $whAuthDate ? $whAuthDate->format('m/d/Y H:i') : 'Not Available',
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
            'delivered_date' => $shipment->formatted_delivered_date ? $shipment->formatted_delivered_date->format('m/d/Y H:i') : 'N/A',
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
                'delivered_date' => 'nullable|date_format:m/d/Y H:i', // Fecha de entrega
                'at_door_date' => 'nullable|date_format:m/d/Y H:i', // Fecha de llegada
                'offloading_time' => 'nullable|date_format:H:i', // Hora de descarga
                'wh_auth_date' => 'nullable|date_format:m/d/Y H:i', // Fecha de autorización
            ]);

            // Convertir las fechas a formato compatible con la base de datos
            if ($request->has('delivered_date') && $request->input('delivered_date')) {
                $validatedData['delivered_date'] = Carbon::createFromFormat('m/d/Y H:i', $request->input('delivered_date'))->format('Y-m-d H:i:s');
            } else {
                $validatedData['delivered_date'] = null;
            }

            if ($request->has('at_door_date') && $request->input('at_door_date')) {
                $validatedData['at_door_date'] = Carbon::createFromFormat('m/d/Y H:i', $request->input('at_door_date'))->format('Y-m-d H:i:s');
            } else {
                $validatedData['at_door_date'] = null;
            }

            if ($request->has('wh_auth_date') && $request->input('wh_auth_date')) {
                $validatedData['wh_auth_date'] = Carbon::createFromFormat('m/d/Y H:i', $request->input('wh_auth_date'))->format('Y-m-d H:i:s');
            } else {
                $validatedData['wh_auth_date'] = null;
            }

            // Actualizar el envío con los nuevos datos
            $shipment->update($validatedData);

            // Devolver respuesta con el estado de la actualización
            return response()->json(['message' => 'Shipment updated successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating shipment: ' . $e->getMessage()], 500);
        }
    }
}
