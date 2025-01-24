<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    //
    public function getServices()
    {
        $data = Services::all();
        
        return response()->json($data);
    }
}
