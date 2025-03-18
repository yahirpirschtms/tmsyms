<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\EmptyTrailer;
use App\Models\GenericCatalog;
use App\Models\Shipments;
use App\Models\Companies;
use App\Models\Facilities;
use App\Models\Driver;
use App\Models\SealsHistory;
use App\Models\TruckHistory;

class DashboardController extends Controller
{
    //
    public function getdashboard(){
        if (Auth::check()) {
            $shipments = Shipments::with(['currentstatus', 'origin', 'destinations'])
            ->get();

            return view('home.dashboard', compact('shipments'));
        }
        return redirect('/login');
    }
}
