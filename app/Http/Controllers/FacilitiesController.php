<?php

namespace App\Http\Controllers;

use App\Models\Facilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FacilitiesController extends Controller
{
    //
    //Sacar todos los Destinations para los shipments
    public function getFacilities(){
    if (Auth::check()) {
        $data = Facilities::all();
        
        return response()->json($data);
    }
    return redirect('/login');
    }
    /*public function getFacilities(Request $request)
    {
        // Obtener el valor de la consulta si existe
        $query = $request->get('query', '');

        // Filtrar las instalaciones si se proporcionó una consulta
        if ($query) {
            $data = Facilities::where('fac_name', 'like', "%$query%")->get();
        } else {
            $data = Facilities::all(); // Si no hay consulta, traer todas las opciones
        }

        return response()->json($data);
    }*/
}
