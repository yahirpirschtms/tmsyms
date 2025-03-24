<?php

namespace App\Http\Controllers;

use App\Models\GenericCatalog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GenericCatalogController extends Controller
{
    public function getDoorNumberWHETAAjax(Request $request){
    if (Auth::check()) {
        $query = GenericCatalog::where('gntc_group', 'WHMIAMI_DOOR_NUMBER');
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('gntc_value', 'like', '%' . $request->search . '%');
        }
    
        $doornumbers = $query->select('gnct_id', 'gntc_value')->get();
    
        return response()->json($doornumbers);
    }
    return redirect('/login');
    }

    public function saveNewDoorNumberWHETA(Request $request){
    if (Auth::check()) {
        // Validar el nombre del carrier
        $request->validate([
            'doorNumberWHETA' => 'required|string|max:255',
        ]);

        // Verificar si el carrier ya existe en la base de datos
        $existingDoorNumberWHETA = GenericCatalog::where('gntc_value', $request->doorNumberWHETA)
        //->orWhere('pk_company', $request->carrierName) // Verificar si el ID del carrier ya existe
        ->first();

        $currentDateTime = now();
        $username = Auth::check() ? Auth::user()->username : 'system';
        
        if (!$existingDoorNumberWHETA) {
            // Si no existe, crear un nuevo carrier
            $newDoorNumberWHETA = new GenericCatalog();
            $newDoorNumberWHETA->gntc_value = $request->doorNumberWHETA;
            $newDoorNumberWHETA->gntc_group = 'WHMIAMI_DOOR_NUMBER'; // Establecer otros campos según sea 
            $newDoorNumberWHETA->gntc_description = $request->doorNumberWHETA;
            $newDoorNumberWHETA->gntc_creation_date = $currentDateTime;
            $newDoorNumberWHETA->gntc_user = $username;
            $newDoorNumberWHETA->save();

            return response()->json([
                'message' => 'New Door Number WH ETA saved successfully.',
                'newDoorNumberWHETA' => [
                    'gnct_id' => $newDoorNumberWHETA->gnct_id,  // Este es el `id` autoincremental
                    'gntc_value' => $newDoorNumberWHETA->gntc_value
                ]
                /*'message' => 'New carrier saved successfully.',
                'newCarrier' => $newCarrier // Devuelves el nuevo carrier guardado*/
            ]);
        } else {
            return response()->json([
                'message' => 'Door Number WH ETA already exists',
                'newDoorNumberWHETA' => $existingDoorNumberWHETA
            ]);
        }
    }
    return redirect('/login');
    }

    
    //Sacar todos los Availability Indicators
    public function getAvailabilityIndicators(){
    if (Auth::check()) {
        $data = GenericCatalog::where('gntc_group', 'AVAILABILITY_INDICATOR')
            //->where('gntc_status', 1) // Filtrar sólo registros activos
            ->select('gnct_id', 'gntc_description')
            ->get();
            
            // Obtener los registros del grupo 'availability_indicator' con el campo `gntc_status` activo
            /*$indicators = GenericCatalog::where('gntc_group', 'availability_indicator')
            ->where('gntc_status', 1)
            ->pluck('gntc_description', 'gnct_id'); // Devuelve un array clave-valor

            return response()->json($indicators); // Retornar los datos como JSON*/

        return response()->json($data);
    }
    return redirect('/login');
    }

    //Sacar todos los Shipment Types
    public function getShipmentTypes(){
    if (Auth::check()) {
        $data = GenericCatalog::where('gntc_group', 'SHIPMENT_TYPE')
            //->where('gntc_status', 1) // Filtrar sólo registros activos
            ->select('gnct_id', 'gntc_description')
            ->get();
            
            // Obtener los registros del grupo 'availability_indicator' con el campo `gntc_status` activo
            /*$indicators = GenericCatalog::where('gntc_group', 'availability_indicator')
            ->where('gntc_status', 1)
            ->pluck('gntc_description', 'gnct_id'); // Devuelve un array clave-valor

            return response()->json($indicators); // Retornar los datos como JSON*/

        return response()->json($data);
    }
    return redirect('/login');
    }

    //Sacar todos los Current Status
    public function getCurrentStatus(){
    if (Auth::check()) {
        $data = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')
            //->where('gntc_status', 1) // Filtrar sólo registros activos
            ->select('gnct_id', 'gntc_description')
            ->get();
            
            // Obtener los registros del grupo 'availability_indicator' con el campo `gntc_status` activo
            /*$indicators = GenericCatalog::where('gntc_group', 'availability_indicator')
            ->where('gntc_status', 1)
            ->pluck('gntc_description', 'gnct_id'); // Devuelve un array clave-valor

            return response()->json($indicators); // Retornar los datos como JSON*/

        return response()->json($data);
    }
    return redirect('/login');
    }

    public function getSecurityCompaniesAjax(Request $request){
    if (Auth::check()) {
        $query = GenericCatalog::where('gntc_group', 'SEC_COMPANY');
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('gntc_value', 'like', '%' . $request->search . '%');
        }
    
        $securitycompanies = $query->select('gnct_id', 'gntc_value')->get();
    
        return response()->json($securitycompanies);
    }
    return redirect('/login');
    }
}
