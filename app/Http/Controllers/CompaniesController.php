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
        $locations = Companies::where('code', 'CBMX')
        ->select('id_company', 'CoName')
        ->get();

        // Retornar los datos en formato JSON
        return response()->json($locations);
    }
}
