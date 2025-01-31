<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    //
    public function getLocations(Request $request)
    {
        // Filtrar por el código "CBMX"
        //$locations = Companies::where('code', 'CBMX')->pluck('CoName', 'pk_company');
        $locations = Companies::where('Notes', 'yms')
        ->select('pk_company', 'CoName')
        ->get();

        // Retornar los datos en formato JSON
        return response()->json($locations);
    }

    public function getCarriersAjax(Request $request)
    {
        $query = Companies::where('Notes', 'yms');
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('CoName', 'like', '%' . $request->search . '%');
        }
    
        $locations = $query->select('pk_company', 'CoName')->get();
    
        return response()->json($locations);
    }
    
    public function saveNewCarrier(Request $request)
    {
        // Validar el nombre del carrier
        $request->validate([
            'carrierName' => 'required|string|max:255',
        ]);

        // Verificar si el carrier ya existe en la base de datos
        $existingCarrier = Companies::where('CoName', $request->carrierName)
        //->orWhere('pk_company', $request->carrierName) // Verificar si el ID del carrier ya existe
        ->first();
        
        if (!$existingCarrier) {
            // Si no existe, crear un nuevo carrier
            $newCarrier = new Companies();
            $newCarrier->CoName = $request->carrierName;
            $newCarrier->Notes = 'yms'; // Establecer otros campos según sea necesario
            $newCarrier->save();

            return response()->json([
                'message' => 'New carrier saved successfully.',
                'newCarrier' => $newCarrier // Devuelves el nuevo carrier guardado
            ]);
        } else {
            return response()->json([
                'message' => 'Carrier already exists',
                'newCarrier' => $existingCarrier
            ]);
        }
    }

}
