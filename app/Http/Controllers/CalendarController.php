<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Support\Facades\Auth;
use App\Models\GenericCatalog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function calendarshow()
    {
        if (Auth::check()) {
            // Obtener el ID del estado 'Finalized' desde el catálogo
            $finalizedStatus = GenericCatalog::where('gntc_value', 'Finalized')
                ->where('gntc_group', 'STATUS_E_REPORT') // Ajustar si hay más grupos
                ->first();

            // Filtrar los envíos que no tienen el estado 'Finalized'
            $shipments = Shipment::where('gnct_id_current_status', '!=', $finalizedStatus->gnct_id)->get();
            $currentStatus = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get();
            // Obtener los catálogos para 'MWD_LOCATION' (origen) y 'STATUS_E_REPORT' (estado actual)
            $originCatalog = GenericCatalog::where('gntc_group', 'MWD_LOCATION')->get()->keyBy('gnct_id');
            $statusCatalog = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get()->keyBy('gnct_id');

            // Obtener el primer envío
            // Obtener el primer envío, o dejar $shipment como null si no hay envíos
             $shipment = $shipments->first() ?? null;



            if (empty($shipment->approved_eta_date)) {
                $shipment->approved_eta_date = null;
            }
            if (empty($shipment->approved_eta_time)) {
                $shipment->approved_eta_time = null;
            }

            // Mapear los datos al formato esperado por FullCalendar
            $events = $shipments->map(function ($shipment) use ($originCatalog, $statusCatalog) {
                // Parsear fechas relevantes con Carbon
                $suggestedDeliveryDate = Carbon::parse($shipment->suggesteddeliverydate);
                $approvedETADate = $shipment->approved_eta_date ? Carbon::parse($shipment->approved_eta_date) : null;
                $approvedETATime = $shipment->approved_eta_time ? Carbon::parse($shipment->approved_eta_time) : null;

                // Obtener las descripciones para 'origin' y 'gnct_id_current_status'
                $originDescription = isset($originCatalog[$shipment->origin])
                    ? $originCatalog[$shipment->origin]->gntc_value
                    : 'Unknown';

                $statusDescription = isset($statusCatalog[$shipment->gnct_id_current_status])
                    ? $statusCatalog[$shipment->gnct_id_current_status]->gntc_value
                    : 'Unknown';

                return [
                    'title' => 'STM ID: ' . $shipment->stm_id,
                    'start' => $suggestedDeliveryDate->format('Y-m-d\TH:i:s'),
                    'end' => $suggestedDeliveryDate->addHours(1)->format('Y-m-d\TH:i:s'),
                    'extendedProps' => [
                        'stm_id' => $shipment->stm_id,
                        'reference' => $shipment->reference,
                        'origin' => $originDescription,
                        'destination' => $shipment->destination,
                        'current_status' => $statusDescription,
                        'suggested_delivery_date' => $suggestedDeliveryDate->format('m/d/Y H:i'),
                        'approved_eta_date' => $approvedETADate ? $approvedETADate->format('m/d/Y') : 'N/A',
                        'approved_eta_time' => $approvedETATime ? $approvedETATime->format('H:i') : 'N/A',
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
                'currentStatus'=> $currentStatus, // Se conserva la variable $shipment
            ]);
        }

        return redirect('/login');
    }


    public function getShipmentDetails($pk_shipment)
    {
        // Buscar el envío por la clave primaria
        $shipment = Shipment::with(['currentStatus', 'originCatalog'])->findOrFail($pk_shipment);

        // Formatear los datos para enviarlos al frontend
        return response()->json([
            'trailer_id' => $shipment->id_trailer,
            'stm_id' => $shipment->stm_id,
            'current_status' => $shipment->currentStatus ? $shipment->currentStatus->gntc_value : 'Unknown',
            'delivered_date' => $shipment->formatted_delivered_date,
            'at_door_date' => $shipment->at_door_date ? $shipment->at_door_date->format('m/d/Y H:i') : 'N/A',
            'offload_date' => $shipment->offload_date ? $shipment->offload_date->format('m/d/Y H:i') : 'N/A',
            'approved_eta_date' => $shipment->approved_eta_date ? $shipment->approved_eta_date->format('m/d/Y') : 'N/A',
            'approved_eta_time' => $shipment->approved_eta_time ? $shipment->approved_eta_time->format('H:i') : 'N/A',
        ]);
    }

    public function updateOffloadingStatus(Request $request, $pk_shipment)
    {


        try {
            // Buscar el envío por pk_shipment
            $shipment = Shipment::findOrFail($pk_shipment);

            // Validar los datos recibidos
            $validatedData = $request->validate([
                'trailer_id' => 'nullable|string|max:255', // ID del remolque
                'stm_id' => 'nullable|integer', // ID del STM
                'current_status' => 'nullable|integer', // Estado actual
                'delivered_date' => 'nullable|date', // Fecha de entrega
                'at_door_date' => 'nullable|date', // Fecha en puerta
                'offload_date' => 'nullable|date', // Fecha de descarga
                'approved_eta_date' => 'nullable|date', // Fecha ETA aprobada
                'approved_eta_time' => 'nullable|date_format:H:i', // Hora ETA aprobada en formato HH:mm
            ]);

            $shipment->update([
                'trailer_id' => $request->trailer_id ?? $shipment->trailer_id, // Solo actualizar si está presente
                'stm_id' => $request->stm_id ?? $shipment->stm_id, // Solo actualizar si está presente
                'gnct_id_current_status' => $request->gnct_id_current_status ?? $shipment->gnct_id_current_status, // Solo actualizar si está presente
                'delivered_date' => $request->delivered_date ?? $shipment->delivered_date, // Solo actualizar si está presente
                'at_door_date' => $request->at_door_date ?? $shipment->at_door_date, // Solo actualizar si está presente
                'offload_date' => $request->offload_date ?? $shipment->offload_date, // Solo actualizar si está presente
                'approved_eta_date' => $request->approved_eta_date ?? $shipment->approved_eta_date, // Solo actualizar si está presente
                'approved_eta_time' => $request->approved_eta_time ?? $shipment->approved_eta_time, // Solo actualizar si está presente
            ]);

            // Responder con un mensaje de éxito
            return response()->json(['message' => 'Shipment updated successfully'], 200);

        } catch (\Exception $e) {
            // Manejar errores y responder con el mensaje de error
            return response()->json(['message' => 'Failed to update shipment', 'error' => $e->getMessage()], 500);
        }
    }
}
