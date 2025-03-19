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
    /*public function getdashboard(){
        if (Auth::check()) {
            $shipments = Shipments::with(['currentstatus', 'origin', 'destinations'])
            ->get();

            return view('home.dashboard', compact('shipments'));
        }
        return redirect('/login');
    }*/
    public function getdashboard()
{
    if (Auth::check()) {
        // Categorías que queremos filtrar
        $categorias = ['Prealerted', 'Driver Assigned', 'In Transit', 'Delivered'];

        // Obtener los estados actuales desde GenericCatalog con el grupo CURRENT_STATUS
        $statusIds = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')
            ->whereIn('gntc_description', $categorias)
            ->pluck('gnct_id', 'gntc_description');

        // Obtener los shipments filtrados por esos estados y categorizarlos
        $shipments = Shipments::with(['currentstatus', 'origin', 'destinations'])
            ->whereIn('gnct_id_current_status', $statusIds)
            ->get()
            ->groupBy(function ($shipment) use ($statusIds) {
                return array_search($shipment->gnct_id_current_status, $statusIds->toArray());
            });

        // Contar cuántos shipments hay en cada categoría
        $shipmentCounts = [];
        foreach ($categorias as $categoria) {
            $shipmentCounts[$categoria] = isset($shipments[$categoria]) ? count($shipments[$categoria]) : 0;
        }

        return view('home.dashboard', compact('shipments', 'shipmentCounts'));
        // Formatear la respuesta JSON
        /*return response()->json([
            'status' => 'success',
            'data' => [
                'shipment_counts' => $shipmentCounts,
                'shipments' => $shipments,
            ]
        ]);
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);*/
    }
    return redirect('/login');
}

}
