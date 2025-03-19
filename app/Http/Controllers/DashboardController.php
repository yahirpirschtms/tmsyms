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
    /*public function getdashboard()
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
        }
        return redirect('/login');
    }*/
    public function getdashboard()
    {
        if (Auth::check()) {
            // Categorías de estados que queremos filtrar
            $categorias = ['Prealerted', 'Driver Assigned', 'In Transit', 'Delivered'];

            // Obtener los status de la tabla GenericCatalog
            $statusIds = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')
                ->whereIn('gntc_description', $categorias)
                ->pluck('gnct_id', 'gntc_description');

            // Obtener los origins válidos
            $origins = Companies::where('Notes', 'LIKE', '%YM%')
                ->whereIn('CoName', ['BW2', 'BW3', 'Foxconn', 'On Time Forwarding', 'Escoto', 'TFEMA Yard', 'TNL Express', 'TNCH Yard'])
                ->pluck('pk_company', 'CoName');

            // Obtener y agrupar los shipments por status y luego por origen
            $shipments = Shipments::with(['currentstatus', 'origin', 'destinations'])
                ->whereIn('gnct_id_current_status', $statusIds)
                ->whereIn('origin', $origins->values()) // Filtrar por origins válidos
                ->get()
                ->groupBy(function ($shipment) use ($statusIds) {
                    return array_search($shipment->gnct_id_current_status, $statusIds->toArray());
                })->map(function ($group) use ($origins) {
                    return $group->groupBy(function ($shipment) use ($origins) {
                        return array_search($shipment->origin->pk_company ?? null, $origins->toArray()) ?? 'Other';
                    });
                });

            // Contar cuántos hay en cada categoría y subcategoría
            $shipmentCounts = [];
            foreach ($categorias as $categoria) {
                $shipmentCounts[$categoria] = [];
                if (isset($shipments[$categoria])) {
                    foreach ($origins as $origin => $pk_company) {
                        $shipmentCounts[$categoria][$origin] = isset($shipments[$categoria][$origin]) 
                            ? count($shipments[$categoria][$origin]) 
                            : 0;
                    }
                }
            }

            return view('home.dashboard', compact('shipments', 'shipmentCounts'));
        }
        return redirect('/login');
    }

    /*public function getupdatedashboard()
    {
        if (Auth::check()) {
            // Categorías a filtrar
            $categorias = ['Prealerted', 'Driver Assigned', 'In Transit', 'Delivered'];
    
            // Obtener los IDs de los estados en la tabla `generic_catalogs`
            $statusIds = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')
                ->whereIn('gntc_description', $categorias)
                ->pluck('gnct_id', 'gntc_description');
    
            // Obtener los IDs de los orígenes en `companies`
            $origins = Companies::where('Notes', 'LIKE', '%YM%')
                ->whereIn('CoName', ['BW2', 'BW3', 'Foxconn', 'On Time Forwarding', 'Escoto', 'TFEMA Yard', 'TNL Express', 'TNCH Yard'])
                ->pluck('pk_company', 'CoName');
    
            // Obtener todos los Shipments con sus relaciones
            $shipments = Shipments::with(['currentstatus', 'origin'])
                ->whereIn('gnct_id_current_status', $statusIds)
                ->whereIn('origin', $origins->values())
                ->get();
    
            // Agrupar por Status y por Origin
            $shipmentCounts = [];
            foreach ($categorias as $categoria) {
                $shipmentCounts[$categoria] = [];
    
                // Buscar el ID correspondiente a esta categoría
                $statusId = $statusIds[$categoria] ?? null;
    
                foreach ($origins as $originName => $pk_company) {
                    // Contar los Shipments que coincidan con el Status y el Origin
                    $count = $shipments->where('gnct_id_current_status', $statusId)
                                       ->where('origin', $pk_company)
                                       ->count();
    
                    $shipmentCounts[$categoria][$originName] = $count;
                }
            }
    
            // Retornar en formato JSON
            return response()->json([
                'status' => 'success',
                'data' => [
                    'shipmentCounts' => $shipmentCounts,
                    'shipments' => $shipments,
                    'origins' => $origins
                ]
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 401);
    }*/

    /*public function getupdatedashboard()
    {
        if (Auth::check()) {
            // Categorías a filtrar
            $categorias = ['Prealerted', 'Driver Assigned', 'In Transit', 'Delivered'];
    
            // Obtener los IDs de los estados en la tabla `generic_catalogs`
            $statusIds = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')
                ->whereIn('gntc_description', $categorias)
                ->pluck('gnct_id', 'gntc_description');
    
            // Obtener los IDs de los orígenes en `companies`
            $origins = Companies::where('Notes', 'LIKE', '%YM%')
                ->whereIn('CoName', ['BW2', 'BW3', 'Foxconn', 'On Time Forwarding', 'Escoto', 'TFEMA Yard', 'TNL Express', 'TNCH Yard'])
                ->pluck('pk_company', 'CoName');
            
            // Obtener los IDs de los destinos en `companies`
            $destinations = Companies::where('Notes', 'LIKE', '%YM%')
                ->whereIn('CoName', ['BW2', 'BW3', 'Foxconn', 'On Time Forwarding', 'Escoto', 'TFEMA Yard', 'TNL Express', 'TNCH Yard, K&N'])
                ->pluck('pk_company', 'CoName');
    
            // Definir las combinaciones de Origen-Destino para "In Transit" y "Delivered"
            $originDestinationPairs = [
                'BW2' => 'On Time Forwarding',
                'BW3' => 'On Time Forwarding',
                'Foxconn' => 'Escoto',
                'On Time Forwarding' => 'TFEMA Yard',
                'On Time Forwarding' => 'TNL Express',
                'Escoto' => 'TNCH Yard',
                'TFEMA Yard' => 'K&N',
                'TNL Express' => 'K&N',
                'TNCH Yard' => 'K&N',
            ];
    
            // Obtener todos los Shipments con sus relaciones
            $shipments = Shipments::with(['currentstatus', 'origin', 'destinations'])
                ->whereIn('gnct_id_current_status', $statusIds)
                ->get();
    
            // Agrupar por Status y por Origin/Destination
            $shipmentCounts = [];
            foreach ($categorias as $categoria) {
                $shipmentCounts[$categoria] = [];
    
                // Buscar el ID correspondiente a esta categoría
                $statusId = $statusIds[$categoria] ?? null;
    
                foreach ($origins as $originName => $pk_company) {
                    // Filtrar para "In Transit" y "Delivered" por destino también
                    if (in_array($categoria, ['In Transit', 'Delivered'])) {
                        foreach ($originDestinationPairs as $origin => $destination) {
                            // Contar los Shipments que coincidan con el Status, Origin y Destination
                            $count = $shipments->where('gnct_id_current_status', $statusId)
                                               ->where('origin', $origin)
                                               ->where('destination', $destination)
                                               ->count();
                            $shipmentCounts[$categoria]["$origin - $destination"] = $count;
                        }
                    } else {
                        // Filtrar solo por Origin para los demás estados
                        $count = $shipments->where('gnct_id_current_status', $statusId)
                                           ->where('origin', $pk_company)
                                           ->count();
                        $shipmentCounts[$categoria][$originName] = $count;
                    }
                }
            }
    
            // Retornar en formato JSON
            return response()->json([
                'status' => 'success',
                'data' => [
                    'shipmentCounts' => $shipmentCounts,
                    'shipments' => $shipments,
                    'origins' => $origins,
                    'destinations' => $originDestinationPairs // Añadimos los destinos aquí
                ]
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 401);
    }
    */

    public function getupdatedashboard()
{
    if (Auth::check()) {
        // Categorías a filtrar
        $categorias = ['Prealerted', 'Driver Assigned', 'In Transit', 'Delivered'];

        // Obtener los IDs de los estados en la tabla `generic_catalogs`
        $statusIds = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')
            ->whereIn('gntc_description', $categorias)
            ->pluck('gnct_id', 'gntc_description');

        // Obtener los IDs de los orígenes
        $origins = Companies::where('Notes', 'LIKE', '%YM%')
            ->whereIn('CoName', ['BW2', 'BW3', 'Foxconn', 'On Time Forwarding', 'Escoto', 'TFEMA Yard', 'TNL Express', 'TNCH Yard'])
            ->pluck('pk_company', 'CoName');

        // Obtener los IDs de los destinos
        $destinations = Companies::whereIn('CoName', [
            'On Time Forwarding', 'Escoto', 'TFEMA Yard', 'TNL Express', 'TNCH Yard', 'K&N'
        ])->pluck('pk_company', 'CoName');

        // Definir los pares de Origen -> Destino en base a nombres
        $originDestinationPairs = [
            'BW2' => 'On Time Forwarding',
            'BW3' => 'On Time Forwarding',
            'Foxconn' => 'Escoto',
            'On Time Forwarding' => 'TFEMA Yard',
            'On Time Forwarding' => 'TNL Express',
            'Escoto' => 'TNCH Yard',
            'TFEMA Yard' => 'K&N',
            'TNL Express' => 'K&N',
            'TNCH Yard' => 'K&N',
        ];

        // Convertir nombres en IDs
        $originDestinationPairsIDs = [];
        foreach ($originDestinationPairs as $originName => $destinationName) {
            if (isset($origins[$originName]) && isset($destinations[$destinationName])) {
                $originDestinationPairsIDs[$origins[$originName]] = $destinations[$destinationName];
            }
        }

        // Obtener todos los Shipments con sus relaciones
        $shipments = Shipments::with(['currentstatus', 'origin', 'destinations'])
            ->whereIn('gnct_id_current_status', $statusIds)
            ->whereIn('origin', $origins->values()) // Solo traer shipments con orígenes válidos
            ->get();

        // Agrupar por Status y por Origin/Destination según la categoría
        $shipmentCounts = [];
        foreach ($categorias as $categoria) {
            $shipmentCounts[$categoria] = [];

            // Buscar el ID correspondiente a esta categoría
            $statusId = $statusIds[$categoria] ?? null;

            if (in_array($categoria, ['In Transit', 'Delivered'])) {
                // Filtrar por Origen y Destino
                foreach ($originDestinationPairsIDs as $originID => $destinationID) {
                    $count = $shipments->where('gnct_id_current_status', $statusId)
                                       ->where('origin', $originID)
                                       ->where('destination', $destinationID)
                                       ->count();
                    // Obtener nombres de origen y destino
                    $originName = array_search($originID, $origins->toArray());
                    $destinationName = array_search($destinationID, $destinations->toArray());

                    // Guardar en el array con los nombres en lugar de los IDs
                    $shipmentCounts[$categoria]["$originName - $destinationName"] = $count;
                }
            } else {
                // Filtrar solo por Origen
                foreach ($origins as $originName => $originID) {
                    $count = $shipments->where('gnct_id_current_status', $statusId)
                                       ->where('origin', $originID)
                                       ->count();
                    $shipmentCounts[$categoria][$originName] = $count;
                }
            }
        }

        // Retornar en formato JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'shipmentCounts' => $shipmentCounts,
                'shipments' => $shipments,
                'origins' => $origins,
                'destinations' => $destinations
            ]
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized'
    ], 401);
}




}
