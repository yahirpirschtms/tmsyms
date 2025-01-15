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
        // Obtener todos los envíos
        $shipments = Shipment::all();

        // Obtener los catálogos para 'MWD_LOCATION' (origen) y 'STATUS_E_REPORT' (estado actual)
        $originCatalog = GenericCatalog::where('gntc_group', 'MWD_LOCATION')->get()->keyBy('gnct_id');
        $statusCatalog = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get()->keyBy('gnct_id');

        // Obtener el primer envío (o uno en específico si lo prefieres)
        $shipment = $shipments->first(); // O ajusta este código según tu lógica para obtener el envío adecuado

        // Mapear los datos al formato esperado por FullCalendar
        $events = $shipments->map(function ($shipment) use ($originCatalog, $statusCatalog) {
            // Parsear fechas relevantes con Carbon
            $suggestedDeliveryDate = Carbon::parse($shipment->suggesteddeliverydate);
            $approvedETADate = Carbon::parse($shipment->approved_eta_date); // Si está disponible
            $approvedETATime = Carbon::parse($shipment->approved_eta_time); // Si está disponible

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
                    'origin' => $originDescription, // Origen traducido
                    'destination' => $shipment->destination,
                    'current_status' => $statusDescription, // Estado traducido
                    'suggested_delivery_date' => $suggestedDeliveryDate->format('m/d/Y H:i'),
                    'approved_eta_date' => $approvedETADate ? $approvedETADate->format('m/d/Y') : 'N/A',
                    'approved_eta_time' => $approvedETATime ? $approvedETATime->format('H:i') : 'N/A',
                    'units' => $shipment->units,
                    'pallets' => $shipment->pallets,
                    'id_trailer' => $shipment->id_trailer,
                ],
            ];
        });

        // Retornar la vista con los datos necesarios, incluyendo $shipment
        return view('home.calendar', [
            'events' => $events->toArray(),
            'shipments' => $shipments,
            'originCatalog' => $originCatalog,
            'statusCatalog' => $statusCatalog, // Pasamos los catálogos al front
            'shipment' => $shipment, // Aquí se pasa el primer envío o el que se necesite
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

            // Validación de los datos
            $validatedData = $request->validate([
                'gnct_id_current_status' => 'nullable|integer', // El campo de estado actual
                'driver_assigned_date' => 'nullable|date', // Fecha de asignación del conductor
                'pick_up_date' => 'nullable|date', // Fecha de recogida
                'intransit_date' => 'nullable|date', // Fecha de tránsito
                'secured_yarddate' => 'nullable|date', // Fecha de depósito asegurado
                'sec_incident' => 'nullable|integer', // Incidente (si aplica)
                'incident_type' => 'nullable|string', // Tipo de incidente (si aplica)
                'incident_date' => 'nullable|date', // Fecha del incidente (si aplica)
            ]);

            // Actualizar el envío con los nuevos datos
            $shipment->update([
                'gnct_id_current_status' => $validatedData['gnct_id_current_status'] ?? $shipment->gnct_id_current_status, // Solo actualizar si el campo no está vacío
                'driver_assigned_date' => $validatedData['driver_assigned_date'],
                'pick_up_date' => $validatedData['pick_up_date'],
                'intransit_date' => $validatedData['intransit_date'],
                'secured_yarddate' => $validatedData['secured_yarddate'],
                'sec_incident' => $validatedData['sec_incident'],
                'incident_type' => $validatedData['incident_type'],
                'incident_date' => $validatedData['incident_date'],
            ]);

            // Responder con un mensaje de éxito
            return response()->json(['message' => 'Shipment updated successfully'], 200);

        } catch (\Exception $e) {
            // Si ocurre un error, responder con el mensaje de error
            return response()->json(['message' => 'Failed to update shipment', 'error' => $e->getMessage()], 500);
        }
    }
}
