<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DriversController extends Controller
{
    //
    //Sacar todos los Drivers para los shipments
    public function getDriversByCompany($id_company){
    if (Auth::check()) {
        // Filtrar los drivers por id_company
        $drivers = Driver::where('id_company', $id_company)->get();

        return response()->json($drivers);
    }
    return redirect('/login');
    }


    public function getDriversAjax(Request $request){
    if (Auth::check()) {
        $query = Driver::query();
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('drivername', 'like', '%' . $request->search . '%');
        }
    
        $drivers = $query->select('pk_driver', 'drivername')->get();
    
        return response()->json($drivers);
    }
    return redirect('/login');
    }
    
    public function saveNewDriver(Request $request){
    if (Auth::check()) {
        // Validar el nombre del carrier
        $request->validate([
            'driversName' => 'required|string|max:255',
        ]);

        // Verificar si el carrier ya existe en la base de datos
        $existingDriver = Driver::where('drivername', $request->driversName)
        //->orWhere('pk_company', $request->carrierName) // Verificar si el ID del carrier ya existe
        ->first();
        
        if (!$existingDriver) {
            // Si no existe, crear un nuevo carrier
            $newDriver = new Driver();
            $newDriver->drivername = $request->driversName;
            //$newDriver->Notes = 'yms'; // Establecer otros campos según sea 
            //$newCarrier->code = $request->code; // Save the code value
            $newDriver->save();

            return response()->json([
                'message' => 'New driver saved successfully.',
                'newDriver' => [
                    'pk_driver' => $newDriver->pk_driver,  // Este es el `id` autoincremental
                    'drivername' => $newDriver->drivername
                ]
                /*'message' => 'New carrier saved successfully.',
                'newCarrier' => $newCarrier // Devuelves el nuevo carrier guardado*/
            ]);
        } else {
            return response()->json([
                'message' => 'Driver already exists',
                'newDriver' => $existingDriver
            ]);
        }
    }
    return redirect('/login');
    }
}

