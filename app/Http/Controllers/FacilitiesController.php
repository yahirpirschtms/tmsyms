<?php

namespace App\Http\Controllers;

use App\Models\Facilities;
use Illuminate\Http\Request;

class FacilitiesController extends Controller
{
    //
    //Sacar todos los Destinations para los shipments
    public function getFacilities()
    {
        $data = Facilities::all();
        
        return response()->json($data);
    }
}
