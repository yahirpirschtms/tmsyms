<?php

namespace App\Http\Controllers;

use App\Models\GenericCatalog;
use Illuminate\Http\Request;

class GenericCatalogController extends Controller
{
    //Sacar todos los Availability Indicators
    public function getAvailabilityIndicators()
    {
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

    //Sacar todos los Shipment Types
    public function getShipmentTypes()
    {
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

    //Sacar todos los Current Status
    public function getCurrentStatus()
    {
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
}
