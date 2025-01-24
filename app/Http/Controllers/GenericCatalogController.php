<?php

namespace App\Http\Controllers;

use App\Models\GenericCatalog;
use Illuminate\Http\Request;

class GenericCatalogController extends Controller
{
    public function getCurrentStatus()
    {
        // Obtener los registros del grupo 'STATUS_E_REPORT'
        $currentStatus = GenericCatalog::where('gntc_group', 'STATUS_E_REPORT')
            ->select('gnct_id', 'gnct_description') // Seleccionamos los campos necesarios
            ->get();

        // Puedes agregar un dump para ver los datos si es necesario
        // dd($currentStatus);

        // Devolver los datos en formato JSON (opcional si quieres consumirlos vía AJAX)
        return response()->json($currentStatus);
    }

    // Método para obtener indicadores de disponibilidad (como lo tenías originalmente)
    public function getAvailabilityIndicators()
    {
        $data = GenericCatalog::where('gnct_group', 'availability_indicator')
            //->where('gntc_status', 1) // Filtrar sólo registros activos
            ->select('gnct_id', 'gnct_description')
            ->get();

        // Retornar los datos en formato JSON
        return response()->json($data);
    }

    public function getOrigin()
    {
    // Obtener el origen de la tabla 'generic_catalogs' donde el grupo sea 'MWD_LOCATION' y el valor '3PA'
    $origin = GenericCatalog::where('gntc_group', 'MWD_LOCATION')
        ->select('gntc_value') // Seleccionamos solo el valor necesario
        ->get();

    // Si se encuentra un origen, retornamos el valor, de lo contrario, retornamos null
    return response()->json($origin);
    }

    //Sacar todos los Current Status
    public function getCurrentStatus()
    {
        $data = GenericCatalog::where('gntc_group', 'current_status')
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
