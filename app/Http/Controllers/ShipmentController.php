<?php

namespace App\Http\Controllers;

use App\Models\Shipments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    //
    /*public function workflowtrafficstartshow()
    {
        if(Auth::check()){
            return view('home.trafficworkflowstart');
        }
        return redirect('/login');

    }*/
    public function allshipmentsshow()
    {
        if(Auth::check()){
            return view('home.shipments');
        }
        return redirect('/login');

    }
    public function emptytrailershow()
    {
        if(Auth::check()){
            return view('home.index');
        }
        return redirect('/login');

    }
    /*public function createWorkflowStartWithEmptyTrailer(Request $request)
    {
        if (Auth::check()) {
            // Recuperar los datos enviados por la URL o sessionStorage
            $origin = $request->query('origin', session('shipment_origin'));
            $trailerId = $request->query('trailerId', session('trailer_id'));

            // Pasar los datos a la vista
            return view('home.trafficworkflowstart', compact('origin', 'trailerId'));
        }

        return redirect('/login');
    }*/

    //Funcione rellenar campos con el empty trailer
    public function createWorkflowStartWithEmptyTrailer(Request $request){
        if (Auth::check()) {
            // Recuperar los datos de la URL o de la sesión
            $trailerId = $request->query('trailerId', session('trailer_id'));
            $status = $request->query('status', session('date_of_status'));
            $palletsontrailer = $request->query('palletsontrailer', session(''));
            $palletsonfloor = $request->query('palletsonfloor', session(''));
            $carrier = $request->query('carrier', session(''));
            $availability = $request->query('availability', session(''));
            $location = $request->query('location', session('shipment_origin'));
            $datein = $request->query('datein', session(''));
            $dateout = $request->query('dateout', session(''));
            $transaction = $request->query('transaction', session(''));
            $username = $request->query('username', session(''));
            
            // Verificar si la solicitud proviene del botón (usamos una bandera)
            $from_button = $request->query('from_button', 1); // 1 si viene del botón, 0 si no

            // Pasar los datos a la vista
            return view('home.trafficworkflowstart', compact('trailerId', 'status', 'palletsontrailer', 
            'palletsonfloor', 'carrier', 'availability', 'location', 'datein', 'dateout', 'transaction', 'username',  'from_button'));
        }

        return redirect('/login');
    }

    public function workflowtrafficstartshow()
    {
        if (Auth::check()) {
            // Verificamos si los datos provienen del botón o no
            $from_button = session('from_button', 0);
            $origin = session('shipment_origin');
            $trailerId = session('trailer_id');
            $status =  session('date_of_status');

            return view('home.trafficworkflowstart', compact('origin', 'trailerId', 'status', 'from_button'));
        }

        return redirect('/login');
    }

    public function store(Request $request)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'inputshipmentstmid' => 'nullable',
            'inputshipmentshipmenttype' => 'nullable',
            'inputshipmentreference' => 'nullable',
            'inputshipmentcheckbonded' => 'nullable',
            'inputorigin' => 'required',
            'inputshipmentdestination' => 'nullable',
            'inputshipmentprealertdatetime' => 'required|date',
            'inputidtrailer' => 'required|exists:empty_trailer,trailer_num',
            'inputshipmentcarrier' => 'nullable',
            'inputshipmenttrailer' => 'nullable',
            'inputshipmenttruck' => 'nullable',
            'inputshipmentdriver' => 'nullable',
            'inputshipmentetd' => 'required|date',
            'inputshipmentsunits' => 'nullable',
            'inputpallets' => 'nullable',
            'inputshipmentsecurityseals' => 'nullable',
            'inputshipmentnotes' => 'nullable',
            'inputshipmentoverhaulid' => 'nullable',
            'inputshipmentdevicenumber' => 'nullable',
        ], [
            'inputidtrailer.required'=>'ID Trailer is required',
            'inputidtrailer.exists' =>'ID Trailer doesnt exists',
            'inputshipmentshipmenttype' => 'Shipment Type is requiered',
            'inputorigin' => 'Origin is required',
            //'inputshipmentdestination' => 'Destination is required',
            'inputshipmentprealertdatetime' => 'PreAlert Date is required',
            //'inputshipmentstmid' => 'STM ID is required',
            'inputshipmentetd' => 'Estimated date of departure is required',

        ]);

        // Convertir las fechas al formato 'm/d/Y'
        $prealertdatetime = Carbon::createFromFormat('m/d/y H:i:s', $request->inputshipmentprealertdatetime)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos
        $estimateddateofdeparture = Carbon::createFromFormat('m/d/y H:i:s', $request->inputshipmentetd)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos

        $bounded = $request->has('inputshipmentcheckbonded') ? true : false;
        // Crear un nuevo registro
        Shipments::create([
            //'pk_trailer' => $request->inputidtrailer,
            'stm_id' => $request->inputshipmentstmid,
            'gnct_id_shipment_type' => $request->inputshipmentshipmenttype,
            'reference' => $request->inputshipmentreference,
            'bonded' => $bounded,
            'origin' => $request->inputorigin,
            'destination' => $request->inputshipmentdestination,
            'pre_alerted_datetime' => $prealertdatetime,
            'id_trailer' => $request->inputidtrailer,
            'id_company' => $request->inputshipmentcarrier,
            'trailer' => $request->inputshipmenttrailer,
            'truck' => $request->inputshipmenttruck,
            'id_driver' => $request->inputshipmentdriver,
            'etd' => $estimateddateofdeparture,
            'units' => $request->inputshipmentsunits,
            'pallets' => $request->inputpallets,
            'security_seals' => $request->inputshipmentsecurityseals,
            'notes' => $request->inputshipmentnotes,
            'overhaul_id' => $request->inputshipmentoverhaulid,
            'device_number' => $request->inputshipmentdevicenumber,
            
        ]);
    
        // Redirigir con mensaje de éxito
        return redirect()->route('workflowtrafficstart')->with('success', 'Shipment successfully added!');
    }

}
