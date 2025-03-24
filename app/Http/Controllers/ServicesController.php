<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ServicesController extends Controller
{
    //
    public function getServices(){
    if (Auth::check()) {
        $data = Services::all();
        
        return response()->json($data);
    }
    return redirect('/login');
    }
}
