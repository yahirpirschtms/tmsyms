<?php

namespace App\Http\Controllers;

use App\Models\GenericCatalog;
use App\Models\Driver;
use App\Models\Companies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GenericCatalogController extends Controller
{
    public function getDoorNumberWHETAAjax(Request $request){
    if (Auth::check()) {
        $query = GenericCatalog::where('gntc_group', 'WHMIAMI_DOOR_NUMBER')
        ->where('gntc_status', 1);
        if ($request->has('search') && !empty($request->search)) {
            $query->where('gntc_value', 'like', '%' . $request->search . '%');
        }
    
        $doornumbers = $query->select('gnct_id', 'gntc_value')->get();
    
        return response()->json($doornumbers);
    }
    return redirect('/login');
    }

    public function saveNewDoorNumberWHETA(Request $request){
    if (Auth::check()) {
        // Validar el nombre del carrier
        $request->validate([
            'doorNumberWHETA' => 'required|string|max:255',
        ]);

        // Verificar si el carrier ya existe en la base de datos
        $existingDoorNumberWHETA = GenericCatalog::where('gntc_value', $request->doorNumberWHETA)
        ->where('gntc_status', 1)
        ->first();

        $currentDateTime = now();
        $username = Auth::check() ? Auth::user()->username : 'system';
        
        if (!$existingDoorNumberWHETA) {
            // Si no existe, crear un nuevo carrier
            $newDoorNumberWHETA = new GenericCatalog();
            $newDoorNumberWHETA->gntc_value = $request->doorNumberWHETA;
            $newDoorNumberWHETA->gntc_group = 'WHMIAMI_DOOR_NUMBER'; // Establecer otros campos según sea 
            $newDoorNumberWHETA->gntc_description = $request->doorNumberWHETA;
            $newDoorNumberWHETA->gntc_creation_date = $currentDateTime;
            $newDoorNumberWHETA->gntc_status = 1;
            $newDoorNumberWHETA->gntc_user = $username;
            $newDoorNumberWHETA->save();

            return response()->json([
                'message' => 'New Door Number WH ETA saved successfully.',
                'newDoorNumberWHETA' => [
                    'gnct_id' => $newDoorNumberWHETA->gnct_id,  // Este es el `id` autoincremental
                    'gntc_value' => $newDoorNumberWHETA->gntc_value
                ]
                /*'message' => 'New carrier saved successfully.',
                'newCarrier' => $newCarrier // Devuelves el nuevo carrier guardado*/
            ]);
        } else {
            return response()->json([
                'message' => 'Door Number WH ETA already exists',
                'newDoorNumberWHETA' => $existingDoorNumberWHETA
            ]);
        }
    }
    return redirect('/login');
    }
    
    //Funcion ara traer los catalogos en la pantalla del WH approval
    public function getInfoGeneric(Request $request){
    if (Auth::check()) {
        $doornumbers = GenericCatalog::where('gntc_group', 'WHMIAMI_DOOR_NUMBER')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();
        $shipmenttypes = GenericCatalog::where('gntc_group', 'SHIPMENT_TYPE')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();

        return response()->json([
            'door_numbers' => $doornumbers,
            'shipment_types' => $shipmenttypes,
        ]);
    }
    return redirect('/login');
    }

    //Funcion ara traer los catalogos en la pantalla del Traffic Workflow Start
    public function getLoadInfo(Request $request){
    if (Auth::check()) {
        $currentstatus = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();
        $shipmenttypes = GenericCatalog::where('gntc_group', 'SHIPMENT_TYPE')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();
        $securitycompanies = GenericCatalog::where('gntc_group', 'SEC_COMPANY')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();
        $drivers = Driver::select('pk_driver', 'drivername')->get();
        $carriers = Companies::where('Notes', 'YM')->select('pk_company', 'CoName')->get();
        $trailerOwners = Companies::where('Notes', 'YM')->select('pk_company', 'CoName')->get();

        return response()->json([
            'current_status' => $currentstatus,
            'shipment_types' => $shipmenttypes,
            'security_companies' => $securitycompanies,
            'drivers' => $drivers,
            'carriers' => $carriers,
            'trailer_owners' => $trailerOwners,
        ]);
    }
    return redirect('/login');
    }

    //Funcion ara traer los catalogos en la pantalla del Empty Trailer
    public function generalCatalogs(Request $request){
    if (Auth::check()) {
        $carriers = Companies::where('Notes', 'YM')->select('pk_company', 'CoName')->get();
        $locations = Companies::where('Notes', 'YM')->select('pk_company', 'CoName')->get();
        $availabilityindicator = GenericCatalog::where('gntc_group', 'AVAILABILITY_INDICATOR')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();

        return response()->json([
            'carriers' => $carriers,
            'locations' => $locations,
            'availability_indicator' => $availabilityindicator,
        ]);
    }
    return redirect('/login');
    }

    //Funcion ara traer los filtros en la pantalla del Empty Trailer
    public function loadCheckBoxfiltersEmptyTrailer(Request $request){
    if (Auth::check()) {
        $currentstatus = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();
        $shipmenttypes = GenericCatalog::where('gntc_group', 'SHIPMENT_TYPE')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();
        $securitycompanies = GenericCatalog::where('gntc_group', 'SEC_COMPANY')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();
        $drivers = Driver::select('pk_driver', 'drivername')->get();
        $carriers = Companies::where('Notes', 'YM')->select('pk_company', 'CoName')->get();
        $locations = Companies::where('Notes', 'YM')->select('pk_company', 'CoName')->get();
        $availabilityindicator = GenericCatalog::where('gntc_group', 'AVAILABILITY_INDICATOR')->where('gntc_status', 1)->select('gnct_id', 'gntc_value')->get();

        return response()->json([
            'current_status' => $currentstatus,
            'shipment_types' => $shipmenttypes,
            'security_companies' => $securitycompanies,
            'drivers' => $drivers,
            'carriers' => $carriers,
            'locations' => $locations,
            'availability_indicator' => $availabilityindicator,
        ]);
    }
    return redirect('/login');
    }


    
    //Sacar todos los Availability Indicators
    public function getAvailabilityIndicators(){
    if (Auth::check()) {
        $data = GenericCatalog::where('gntc_group', 'AVAILABILITY_INDICATOR')
            ->where('gntc_status', 1) // Filtrar sólo registros activos
            ->select('gnct_id', 'gntc_description')
            ->get();
            
            // Obtener los registros del grupo 'availability_indicator' con el campo `gntc_status` activo
            /*$indicators = GenericCatalog::where('gntc_group', 'availability_indicator')
            ->where('gntc_status', 1)
            ->pluck('gntc_description', 'gnct_id'); // Devuelve un array clave-valor

            return response()->json($indicators); // Retornar los datos como JSON*/

        return response()->json($data);
    }
    return redirect('/login');
    }

    //Sacar todos los Shipment Types
    public function getShipmentTypes(){
    if (Auth::check()) {
        $data = GenericCatalog::where('gntc_group', 'SHIPMENT_TYPE')
            ->where('gntc_status', 1) // Filtrar sólo registros activos
            ->select('gnct_id', 'gntc_description')
            ->get();
            
            // Obtener los registros del grupo 'availability_indicator' con el campo `gntc_status` activo
            /*$indicators = GenericCatalog::where('gntc_group', 'availability_indicator')
            ->where('gntc_status', 1)
            ->pluck('gntc_description', 'gnct_id'); // Devuelve un array clave-valor

            return response()->json($indicators); // Retornar los datos como JSON*/

        return response()->json($data);
    }
    return redirect('/login');
    }

    //Sacar todos los Current Status
    public function getCurrentStatus(){
    if (Auth::check()) {
        $data = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')
            ->where('gntc_status', 1) // Filtrar sólo registros activos
            ->select('gnct_id', 'gntc_description')
            ->get();
            
            // Obtener los registros del grupo 'availability_indicator' con el campo `gntc_status` activo
            /*$indicators = GenericCatalog::where('gntc_group', 'availability_indicator')
            ->where('gntc_status', 1)
            ->pluck('gntc_description', 'gnct_id'); // Devuelve un array clave-valor

            return response()->json($indicators); // Retornar los datos como JSON*/

        return response()->json($data);
    }
    return redirect('/login');
    }

    public function getSecurityCompaniesAjax(Request $request){
    if (Auth::check()) {
        $query = GenericCatalog::where('gntc_group', 'SEC_COMPANY')
        ->where('gntc_status', 1);
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('gntc_value', 'like', '%' . $request->search . '%');
        }
    
        $securitycompanies = $query->select('gnct_id', 'gntc_value')->get();
    
        return response()->json($securitycompanies);
    }
    return redirect('/login');
    }
}
