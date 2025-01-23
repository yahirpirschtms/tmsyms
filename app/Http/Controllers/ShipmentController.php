<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shipment;
use App\Models\GenericCatalog;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{


    public function allshipmentsshow()
    {
     if (Auth::check()) {
        $shipments = Shipment::all();  // Obtén los envíos desde la base de datos
          // Esto te ayudará a verificar si los envíos se están obteniendo correctamente

          // Obtener los estados actuales desde la base de datos (usando un modelo genérico como ejemplo)
        $currentStatus = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get();
        return view('home.all-shipments', compact('shipments', 'currentStatus'));  // Cambia aquí el nombre de la vista
        }
     return redirect('/login');
    }


    public function liveshipmentsshow()
    {
        if (Auth::check()) {
            // Obtener el ID del estado 'Finalized' desde el catálogo
            $finalizedStatus = GenericCatalog::where('gntc_value', 'Finalized')
                ->where('gntc_group', 'STATUS_E_REPORT')
                ->first();

            // Filtrar los envíos que no tienen el estado 'Finalized'
            $shipments = Shipment::where('gnct_id_current_status', '!=', $finalizedStatus->gnct_id)->get();

            // Obtener los estados actuales desde la base de datos
            $currentStatus = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get();
            // Obtener los estados actuales desde la base de datos
            $shipmentType = GenericCatalog::where('gntc_group', 'TYPE')->get();

            // Pasar los envíos y los estados a la vista
            return view('home.liveshipments', compact('shipments', 'currentStatus', 'shipmentType'));
        }

        return redirect('/login');
    }

    // Listar envíos
    public function index()
    {
        $origins = Shipment::select('origin')->distinct()->get();
        $shipments = Shipment::all();

        return view('shipments', compact('shipments', 'origins'));
    }

    // Crear un nuevo envío
    public function store(Request $request)
    {
        $validated = $request->validate([
            'stm_id' => 'required|string|max:50',
            'reference' => 'required|string|max:50',
            'origin' => 'required|string|max:50',
            'destination' => 'required|string|max:50',
            'etd' => 'nullable|date',
            'units' => 'nullable|string|max:50',
        ]);

        Shipment::create($validated);

        return redirect()->back()->with('success', 'Envío creado exitosamente.');
    }


    public function details($pk_shipment)
    {
        // Obtener el envío con las relaciones de currentStatus, driver y originCatalog
        $shipment = Shipment::with(['currentStatus', 'driver', 'originCatalog'])->findOrFail($pk_shipment);

        // Obtener los estatus disponibles bajo el grupo 'STATUS_E_REPORT'
        $currentStatus = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')->get();

        // Pasar las variables a la vista
        return view('shipments.details', compact('shipment', 'currentStatus'));
    }


    public function update(Request $request, $pk_shipment)
    {



        try {
            // Buscar el envío por su ID
            $shipment = Shipment::findOrFail($pk_shipment);

            // Validar los datos recibidos
            $validated = $request->validate([
                'gnct_id_current_status' => 'nullable|integer', // El campo de estado actual (deshabilitado en el formulario)
                'driver_assigned_date' => 'nullable|date', // Fecha de asignación del conductor
                'pick_up_date' => 'nullable|date', // Fecha de recogida
                'intransit_date' => 'nullable|date', // Fecha de tránsito
                'secured_yarddate' => 'nullable|date', // Fecha de depósito asegurado
                'sec_incident' => 'nullable|integer', // Incidente (si aplica)
                'incident_type' => 'nullable|string', // Tipo de incidente (si aplica)
                'incident_date' => 'nullable|date', // Fecha del incidente (si aplica)
            ]);

            // Actualizar los datos del envío
            $shipment->update([
                'gnct_id_current_status' => $request->gnct_id_current_status ?? $shipment->gnct_id_current_status, // Solo actualizar si el campo no está vacío
                'driver_assigned_date' => $request->driver_assigned_date,
                'pick_up_date' => $request->pick_up_date,
                'intransit_date' => $request->intransit_date,
                'secured_yarddate' => $request->secured_yarddate,
                'sec_incident' => $request->sec_incident,
                'incident_type' => $request->incident_type,
                'incident_date' => $request->incident_date,
            ]);

            // Redirigir a la lista de envíos con un mensaje de éxito
            return response()->json(['message' => 'Shipment updated successfully'], 200);
        } catch (\Exception $e) {
            // Si ocurre un error, redirigir con un mensaje de error

            return response()->json(['message' => 'Failed to update shipment', 'error' => $e->getMessage()], 500);
        }
    }

    // Método para actualizar las notas del envío
    public function updateNotes(Request $request, Shipment $shipment)
    {
        // Validar y actualizar las notas
        $request->validate([
            'notes' => 'required|string|max:255',
        ]);

        $shipment->notes = $request->notes;
        $shipment->save();

        // Redirigir o devolver una respuesta
        return redirect()->route('shipments.index')->with('success', 'Notes updated successfully!');
    }


    // Eliminar un envío
    public function destroy($id)
    {
        Shipment::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Envío eliminado exitosamente.');
    }
}
