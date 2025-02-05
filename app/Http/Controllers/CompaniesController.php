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
        $locations = Companies::where('Notes', 'YM')
        ->select('pk_company', 'CoName')
        ->get();

        // Retornar los datos en formato JSON
        return response()->json($locations);
    }

    public function getLocationsAjax(Request $request){
        $query = Companies::where('Notes', 'YM');
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('CoName', 'like', '%' . $request->search . '%');
        }
    
        $locations = $query->select('pk_company', 'CoName')->get();
    
        return response()->json($locations);
    }

    public function saveNewLocation(Request $request)
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
            $newCarrier->Notes = 'YM'; // Establecer otros campos según sea 
            //$newCarrier->code = $request->code; // Save the code value
            $newCarrier->save();

            return response()->json([
                'message' => 'New location saved successfully.',
                'newCarrier' => [
                    'pk_company' => $newCarrier->pk_company,  // Este es el `id` autoincremental
                    'CoName' => $newCarrier->CoName
                ]
                /*'message' => 'New carrier saved successfully.',
                'newCarrier' => $newCarrier // Devuelves el nuevo carrier guardado*/
            ]);
        } else {
            return response()->json([
                'message' => 'Location already exists',
                'newCarrier' => $existingCarrier
            ]);
        }
    }

    public function getCarriersAjax(Request $request)
    {
        $query = Companies::where('Notes', 'YM');
    
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
            $newCarrier->Notes = 'YM'; // Establecer otros campos según sea 
            //$newCarrier->code = $request->code; // Save the code value
            $newCarrier->save();

            return response()->json([
                'message' => 'New carrier saved successfully.',
                'newCarrier' => [
                    'pk_company' => $newCarrier->pk_company,  // Este es el `id` autoincremental
                    'CoName' => $newCarrier->CoName
                ]
                /*'message' => 'New carrier saved successfully.',
                'newCarrier' => $newCarrier // Devuelves el nuevo carrier guardado*/
            ]);
        } else {
            return response()->json([
                'message' => 'Carrier already exists',
                'newCarrier' => $existingCarrier
            ]);
        }
    }

    public function getTrailerOwnersAjax(Request $request)
    {
        $query = Companies::where('Notes', 'YM');
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('CoName', 'like', '%' . $request->search . '%');
        }
    
        $locations = $query->select('pk_company', 'CoName')->get();
    
        return response()->json($locations);
    }

    public function saveNewTrailerOwner(Request $request)
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
            $newCarrier->Notes = 'YM'; // Establecer otros campos según sea 
            //$newCarrier->code = $request->code; // Save the code value
            $newCarrier->save();

            return response()->json([
                'message' => 'New Trailer Owner saved successfully.',
                'newCarrier' => [
                    'pk_company' => $newCarrier->pk_company,  // Este es el `id` autoincremental
                    'CoName' => $newCarrier->CoName
                ]
                /*'message' => 'New carrier saved successfully.',
                'newCarrier' => $newCarrier // Devuelves el nuevo carrier guardado*/
            ]);
        } else {
            return response()->json([
                'message' => 'Trailer Owner already exists',
                'newCarrier' => $existingCarrier
            ]);
        }
    }

    public function getDestinationsAjax(Request $request){

        $query = $request->get('query', '');

        // Filtrar solo las compañías donde Notes sea "yms"
        $queryBuilder = Companies::where('Notes', 'YM');

        // Si hay una búsqueda, filtrar por CoName además de Notes
        if ($query) {
            $queryBuilder->where('CoName', 'like', "%$query%");
        }

        $data = $queryBuilder->get();

        return response()->json($data);
    }

}
