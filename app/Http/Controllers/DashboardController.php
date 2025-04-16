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
                ->where('gntc_status', 1) // Solo estados activos
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

    /*public function getupdatedashboard(){
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

            // Definir los pares de Origen -> Destino en base a nombres como arrays
            $originDestinationPairs = [
                'BW2' => ['On Time Forwarding'],
                'BW3' => ['On Time Forwarding'],
                'Foxconn' => ['Escoto'],
                'On Time Forwarding' => ['TFEMA Yard', 'TNL Express'], // 🔹 Ahora es un array
                'Escoto' => ['TNCH Yard'],
                'TFEMA Yard' => ['K&N'],
                'TNL Express' => ['K&N'],
                'TNCH Yard' => ['K&N'],
            ];

            // Convertir nombres en IDs
            $originDestinationPairsIDs = [];
            foreach ($originDestinationPairs as $originName => $destinationNames) {
                if (isset($origins[$originName])) {
                    foreach ($destinationNames as $destinationName) {
                        if (isset($destinations[$destinationName])) {
                            $originDestinationPairsIDs[] = [
                                'origin' => $origins[$originName],
                                'destination' => $destinations[$destinationName]
                            ];
                        }
                    }
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
                    foreach ($originDestinationPairsIDs as $pair) {
                        $count = $shipments->where('gnct_id_current_status', $statusId)
                                        ->where('origin', $pair['origin'])
                                        ->where('destination', $pair['destination'])
                                        ->count();
                        // Obtener nombres de origen y destino
                        $originName = array_search($pair['origin'], $origins->toArray());
                        $destinationName = array_search($pair['destination'], $destinations->toArray());

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


            $emptyTrailers = EmptyTrailer::with('locations')
            ->whereIn('location', $origins->values()) // Filtrar solo por los orígenes válidos
            ->whereNull('availability') 
            ->get();

            // Agrupar Empty Trailers por `location`
            $emptyTrailerCounts = [];
            foreach ($emptyTrailers as $trailer) {
                $locationName = $trailer->locations ? $trailer->locations->CoName : 'Desconocido';

                if (!isset($emptyTrailerCounts[$locationName])) {
                    $emptyTrailerCounts[$locationName] = 0;
                }
                $emptyTrailerCounts[$locationName]++;
            }

            // Retornar en formato JSON
            return response()->json([
                'status' => 'success',
                'data' => [
                    'shipmentCounts' => $shipmentCounts,
                    'shipments' => $shipments,
                    'origins' => $origins,
                    'destinations' => $destinations,
                    'emptyTrailerCounts' => $emptyTrailerCounts, // 🔹 Agregamos el conteo de Empty Trailers
                    'emptyTrailers' => $emptyTrailers, // 🔹 Agregamos la lista completa de trailers vacíos
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 401);
    }*/

public function getupdatedashboard() {
    if (Auth::check()) {
        // Categorías a filtrar
        $categorias = ['Prealerted', 'Driver Assigned', 'In Transit', 'Delivered'];

        // Obtener IDs de estados en la tabla `generic_catalogs`
        $statusIds = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')
            ->whereIn('gntc_description', $categorias)
            ->where('gntc_status', 1) // Solo estados activos
            ->pluck('gnct_id', 'gntc_description');

        // Obtener IDs de orígenes y destinos
        $origins = Companies::where('Notes', 'LIKE', '%YM%')
            ->whereIn('CoName', ['BW2', 'BW3', 'Foxconn', 'On Time Forwarding', 'Escoto', 'TFEMA Yard', 'TNL Express', 'TNCH Yard'])
            ->pluck('pk_company', 'CoName');

        $destinations = Companies::whereIn('CoName', [
            'On Time Forwarding', 'Escoto', 'TFEMA Yard', 'TNL Express', 'TNCH Yard', 'K&N'
        ])->pluck('pk_company', 'CoName');

        // Pairs de origen/destino
        $originDestinationPairs = [
            'BW2' => ['On Time Forwarding'],
            'BW3' => ['On Time Forwarding'],
            'Foxconn' => ['Escoto'],
            'On Time Forwarding' => ['TFEMA Yard', 'TNL Express'],
            'Escoto' => ['TNCH Yard'],
            'TFEMA Yard' => ['K&N'],
            'TNL Express' => ['K&N'],
            'TNCH Yard' => ['K&N'],
        ];

        // Convertir nombres a IDs
        $originDestinationPairsIDs = [];
        foreach ($originDestinationPairs as $originName => $destinationNames) {
            if (isset($origins[$originName])) {
                foreach ($destinationNames as $destinationName) {
                    if (isset($destinations[$destinationName])) {
                        $originDestinationPairsIDs[] = [
                            'origin' => $origins[$originName],
                            'destination' => $destinations[$destinationName]
                        ];
                    }
                }
            }
        }

        // Obtener todos los Shipments con relaciones
        $shipments = Shipments::with(['currentstatus', 'origin', 'destinations'])
            ->whereIn('gnct_id_current_status', $statusIds)
            ->whereIn('origin', $origins->values())
            ->get();

        // Definir el rango de fechas para "Delivered"
        $weekAgo = Carbon::now()->subWeek()->startOfDay(); // Hace una semana
        $today = Carbon::now()->endOfDay(); // Hoy a las 23:59:59

        // Agrupar por Status y por Origin/Destination
        $shipmentCounts = [];
        foreach ($categorias as $categoria) {
            $shipmentCounts[$categoria] = [];

            // Buscar el ID correspondiente a esta categoría
            $statusId = $statusIds[$categoria] ?? null;

            if (in_array($categoria, ['In Transit', 'Delivered'])) {
                foreach ($originDestinationPairsIDs as $pair) {
                    // Filtro adicional para "Delivered"
                    if ($categoria === 'Delivered') {
                        $count = Shipments::where('gnct_id_current_status', $statusId)
                            ->where('origin', $pair['origin'])
                            ->where('destination', $pair['destination'])
                            ->whereBetween('delivered_date', [$weekAgo, $today]) // 🔥 Filtrar por fecha
                            ->count();
                    } else {
                        $count = Shipments::where('gnct_id_current_status', $statusId)
                            ->where('origin', $pair['origin'])
                            ->where('destination', $pair['destination'])
                            ->count();
                    }

                    // Obtener nombres de origen y destino
                    $originName = array_search($pair['origin'], $origins->toArray());
                    $destinationName = array_search($pair['destination'], $destinations->toArray());

                    // Guardar resultado
                    $shipmentCounts[$categoria]["$originName - $destinationName"] = $count;
                }
            } else {
                foreach ($origins as $originName => $originID) {
                    $count = Shipments::where('gnct_id_current_status', $statusId)
                        ->where('origin', $originID)
                        ->count();
                    $shipmentCounts[$categoria][$originName] = $count;
                }
            }
        }

        // Obtener los Empty Trailers
        $emptyTrailers = EmptyTrailer::with('locations')
            ->whereIn('location', $origins->values())
            ->whereNull('availability')
            ->get();

        // Agrupar Empty Trailers por `location`
        $emptyTrailerCounts = [];
        foreach ($emptyTrailers as $trailer) {
            $locationName = $trailer->locations ? $trailer->locations->CoName : 'Desconocido';

            if (!isset($emptyTrailerCounts[$locationName])) {
                $emptyTrailerCounts[$locationName] = 0;
            }
            $emptyTrailerCounts[$locationName]++;
        }

        // Retornar JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'shipmentCounts' => $shipmentCounts,
                'shipments' => $shipments,
                'origins' => $origins,
                'destinations' => $destinations,
                'emptyTrailerCounts' => $emptyTrailerCounts,
                'emptyTrailers' => $emptyTrailers,
            ]
        ]);
    }
    return redirect('/login');
    /*return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized'
    ], 401);*/
}


}
