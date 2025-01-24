<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriversController extends Controller
{
    //
    //Sacar todos los Drivers para los shipments
    public function getDriversByCompany($id_company)
    {
        // Filtrar los drivers por id_company
        $drivers = Driver::where('id_company', $id_company)->get();

        return response()->json($drivers);
    }
}

