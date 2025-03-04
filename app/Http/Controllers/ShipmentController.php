<?php

namespace App\Http\Controllers;

use App\Models\EmptyTrailer;
use App\Models\GenericCatalog;
use App\Models\Shipments;
use App\Models\Companies;
use App\Models\Facilities;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


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
    /*public function allshipmentsshow()
    {
        if(Auth::check()){
            return view('home.shipments');
        }
        return redirect('/login');

    }*/
    // Listar envíos
    public function index()
    {
        $origins = Shipments::select('origin')->distinct()->get();
        $shipments = Shipments::all();

        return view('shipments', compact('shipments', 'origins'));
    }

    public function allshipmentsshow()
{
    if (Auth::check()) {
        // Obtener todos los envíos desde la base de datos
        $shipments = Shipments::all();


        // Obtener los estados actuales desde la base de datos
        $currentStatus = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')->get();

        // Obtener los tipos de envío desde la base de datos
        $shipmentType = GenericCatalog::where('gntc_group', 'SHIPMENT_TYPE')->get();

        // Obtener todas las compañías para los selects
        $companies = Companies::where('notes', 'ym')->get();

        $facilities = Facilities::all();

        $trailers = EmptyTrailer::all();
         // Obtener la lista de conductores
        $drivers = Driver::all();

        // Pasar los envíos, estados, tipos de envío y compañías a la vista
        return view('home.all-shipments', compact('shipments', 'currentStatus', 'shipmentType', 'companies', 'facilities', 'trailers', 'drivers'));
    }
    return redirect('/login');
}

    public function liveshipmentsshow()
    {
        if (Auth::check()) {
            // Obtener el ID del estado 'Finalized' desde el catálogo
            $finalizedStatus = GenericCatalog::where('gntc_value', 'Finalized')
                ->where('gntc_group', 'CURRENT_STATUS')
                ->first();

            // Filtrar los envíos que no tienen el estado 'Finalized'
            $shipments = Shipments::where('gnct_id_current_status', '!=', $finalizedStatus->gnct_id)->get();

            // Obtener los estados actuales desde la base de datos
            $currentStatus = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')->get();

            // Obtener los tipos de envío desde la base de datos
            $shipmentType = GenericCatalog::where('gntc_group', 'SHIPMENT_TYPE')->get();

            // Obtener todas las compañías para los selects
            $companies = Companies::where('notes', 'ym')->get();

            $facilities = Facilities::all();

            $trailers = EmptyTrailer::all();
             // Obtener la lista de conductores
            $drivers = Driver::all();

            // Pasar los envíos, estados, tipos de envío y compañías a la vista
            return view('home.liveshipments', compact('shipments', 'currentStatus', 'shipmentType', 'companies', 'facilities', 'trailers', 'drivers'));
        }

        return redirect('/login');
    }

    public function details($pk_shipment)
{
    // Obtener el envío con las relaciones de currentStatus, driver y originCatalog
    $shipment = Shipments::with(['currentStatus', 'driver', 'originCatalog'])->findOrFail($pk_shipment);

    // Formatear las fechas en el formato M/D/Y
    $shipment->pre_alerted_datetime = \Carbon\Carbon::parse($shipment->pre_alerted_datetime)->format('m/d/Y');
    $shipment->delivered_date = \Carbon\Carbon::parse($shipment->delivered_date)->format('m/d/Y');
    $shipment->at_door_date = \Carbon\Carbon::parse($shipment->at_door_date)->format('m/d/Y');
    $shipment->driver_assigned_date = \Carbon\Carbon::parse($shipment->driver_assigned_date)->format('m/d/Y');
    $shipment->pick_up_date = \Carbon\Carbon::parse($shipment->pick_up_date)->format('m/d/Y');
    $shipment->intransit_date = \Carbon\Carbon::parse($shipment->intransit_date)->format('m/d/Y');
    $shipment->secured_yarddate = \Carbon\Carbon::parse($shipment->secured_yarddate)->format('m/d/Y');
    $shipment->wh_auth_date = \Carbon\Carbon::parse($shipment->wh_auth_date)->format('m/d/Y');

    // Obtener los estatus disponibles bajo el grupo 'STATUS_E_REPORT'
    $currentStatus = GenericCatalog::where('gntc_group', 'CURRENT_STATUS')->get();

    // Pasar las variables a la vista
    return view('shipments.details', compact('shipment', 'currentStatus'));
}
    public function getStatusIdByDescription(Request $request)
    {
        $description = $request->input('description');
        $status = GenericCatalog::table('generic_catalogs')
                    ->where('gntc_description', $description)
                    ->first();

        if ($status) {
            return response()->json(['status_id' => $status->gntc_id]);
        } else {
            return response()->json(['error' => 'Estado no encontrado'], 404);
        }
    }

    public function update(Request $request, $pk_shipment)
    {

        try {

            // Buscar el envío por su ID
            $shipment = Shipments::findOrFail($pk_shipment);

            // Validar los datos recibidos
            $validated = $request->validate([
                'gnct_id_current_status' => 'nullable|integer',
                'driver_assigned_date' => 'nullable|string',
                'pick_up_date' => 'nullable|string',
                'intransit_date' => 'nullable|string',
                'secured_yarddate' => 'nullable|string',
                'sec_incident' => 'nullable|integer',
                'incident_type' => 'nullable|string',
                'incident_date' => 'nullable|string',
                'device_number' => 'nullable|string',
                'overhaul_id' => 'nullable|string',
                'secondary_shipment_id' => 'nullable|string',
                'reference' => 'nullable|string',
                'shipment_type' => 'nullable|string',
                'etd' => 'nullable|string',
                'origin' => 'nullable|integer',
                'destination' => 'nullable|integer',
                'pre_alerted_datetime' => 'nullable|string',
                'trailer_owner' => 'nullable|integer',
                'carrier' => 'nullable|integer',
                'id_driver' => 'nullable|integer',
                'units' => 'nullable|string',
                'pallets' => 'nullable|string',
                'security_seals' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            // Convertir fechas del formato m/d/Y H:i al formato Y-m-d H:i:s para MySQL
            foreach (['driver_assigned_date', 'pick_up_date', 'intransit_date', 'secured_yarddate', 'incident_date', 'pre_alerted_datetime', 'etd'] as $field) {
    if (!empty($validated[$field])) {
        if ($field == 'pre_alerted_datetime') {
            // Para pre_alerted_datetime, convertir a Y-m-d\TH:i
            $validated[$field] = Carbon::createFromFormat('m/d/Y H:i', $validated[$field])->format('Y-m-d H:i:s');
        } else {
            // Para otros campos, mantener la conversión original
            $validated[$field] = Carbon::createFromFormat('m/d/Y H:i', $validated[$field])->format('Y-m-d H:i:s');
        }
    } else {
        $validated[$field] = null;
    }
}

            // Actualizar el envío con los datos validados
            $shipment->update($validated);

            return response()->json(['message' => 'Shipment updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update shipment', 'error' => $e->getMessage()], 500);
        }
    }
    // Método para actualizar las notas del envío
    public function updateNotes(Request $request, Shipments $shipment)
    {
        // Validar y actualizar las notas
        $request->validate([
            'notes' => 'required|string|max:255',
        ]);

        $shipment->notes = $request->notes;
        $shipment->save();

        // Redirigir o devolver una respuesta
        return redirect()->route('shipments.index')->with('success', 'Notes updated successfully!');
    }

    //Cambiar a la pantalla de creacion empty trailes
    public function emptytrailershow()
    {
        if(Auth::check()){
            return view('home.index');
        }
        return redirect('/login');

    }

    //Funcione rellenar campos del shipment con el empty trailer
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
            //$dateout = $request->query('dateout', session(''));
            //$transaction = $request->query('transaction', session(''));
            $username = $request->query('username', session(''));

            // Verificar si la solicitud proviene del botón (usamos una bandera)
            $from_button = $request->query('from_button', 1); // 1 si viene del botón, 0 si no

            // Pasar los datos a la vista
            return view('home.trafficworkflowstart', compact('trailerId', 'status', 'palletsontrailer',
            'palletsonfloor', 'carrier', 'availability', 'location', 'datein',/* 'dateout', 'transaction',*/ 'username',  'from_button'));
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
            'inputshipmentstmid' => 'required|unique:shipments,stm_id|exists:services,id_service',
            'inputshipmentshipmenttype' => 'required|exists:generic_catalogs,gnct_id',
            'inputshipmentreference' => 'nullable',
            'inputshipmentcheckbonded' => 'nullable',
            //'inputorigin' => 'required|exists:companies,pk_company',
            'inputorigin' => 'required',
            'inputshipmentdestination' => 'required',
            'inputshipmentprealertdatetime' => 'required|date',
            //'inputidtrailer' => 'required|unique:shipments,id_trailer|exists:empty_trailer,trailer_num',
            'inputidtrailer' => 'required|unique:shipments,id_trailer',
            //'inputshipmentcarrier' => 'required|exists:companies,pk_company',
            'inputshipmentcarrier' => 'nullable',
            'inputshipmenttrailer' => 'nullable',
            'inputshipmenttruck' => 'nullable',
            'inputshipmentdriver' => 'nullable',
            'inputshipmentetd' => 'required|date',
            'inputshipmentsunits' => 'required|min:1|integer',
            'inputpallets' => [
        '', 
        'required', 
        'min:1', // No puede ser nulo ni 0
        function ($attribute, $value, $fail) use ($request) {
            // Mensaje personalizado si no es un número entero
            $valuee = intval($value); // Asegurarse de que el valor es un entero
            if($value < 1){
                $fail('Pallets must have a valid value.');
            }
            if (!is_int($valuee)) {
                $fail('Pallets must be an integer.');
            }
            if ($request->input('inputshipmentsunits') !== null && $value > $request->input('inputshipmentsunits')) {
                $fail('The number of pallets cannot be greater than the number of shipment units.');
            }
        },
    ],
            'inputshipmentsecurityseals' => 'nullable',
            'inputshipmentsecurityseals2' => 'nullable',
            'inputshipmentnotes' => 'nullable',
            'inputshipmentoverhaulid' => 'nullable',
            'inputshipmentdevicenumber' => 'nullable',
            'tracker2' => 'nullable',
            'tracker3' => 'nullable',
            'inputshipmentcurrentstatus' => 'required|exists:generic_catalogs,gnct_id'
        ], [
            'inputidtrailer.required'=>'ID Trailer is required',
            //'inputidtrailer.exists' =>'ID Trailer doesnt exists',
            'inputidtrailer.unique' =>'ID Trailer has already been taken',
            'inputshipmentstmid.required'=>'ID STM is required',
            'inputshipmentstmid.exists'=>'ID STM doesnt exists',
            'inputshipmentstmid.unique'=>'ID STM has already been taken',
            'inputshipmentshipmenttype.required' => 'Shipment Type is requiered',
            'inputshipmentshipmenttype.exists' => 'Shipment Type doesnt exists',
            'inputorigin.required' => 'Origin is required',
            //'inputorigin.exists' => 'Origin doesnt exists',
            'inputshipmentdestination.required' => 'Destination is required',
            //'inputshipmentdestination.exists' => 'Destination doesnt exists',
            'inputshipmentcarrier.required' => 'Carrier is required',
            //'inputshipmentcarrier.exists' => 'Carrier doesnt exists',
            'inputshipmenttrailer.required' => 'Trailer Owner is required',
            //'inputshipmentdriver.exists' => 'Driver doesnt exists',
            'inputshipmentprealertdatetime.required' => 'PreAlert Date is required',
            'inputshipmentprealertdatetime.date' => 'Format no valid',
            'inputshipmentetd.required' => 'Estimated date of departure is required',
            'inputshipmentetd.date' => 'Estimated date of departure format is invalid',
            'inputshipmentcurrentstatus.required' => 'Current Status is required',
            'inputshipmentcurrentstatus.exists' => 'Current Status doesnt exists',
            'inputshipmentsunits.required' => 'Unist are required',
            'inputshipmentsunits.integer' => 'Units must be an integer',
            'inputshipmentsunits.min' => 'Units must have a valid value',
            'inputpallets.required' => 'Pallets are required',
            'inputpallets.integer' => 'Pallets must be an integer',
            'inputpallets.min' => 'Pallets must have a valid value',
        ]);

        // Convertir las fechas al formato 'm/d/Y'
        $prealertdatetime = Carbon::createFromFormat('m/d/y H:i:s', $request->inputshipmentprealertdatetime)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos
        $estimateddateofdeparture = Carbon::createFromFormat('m/d/y H:i:s', $request->inputshipmentetd)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos

        $bounded = $request->has('inputshipmentcheckbonded') ? true : false;
        //$bounded = $request->has('inputshipmentcheckbonded') ? 'Bonded' : 'Not Bonded';

        // Buscar o crear el trailer
        $trailer = EmptyTrailer::firstOrCreate(
            ['trailer_num' => $request->inputidtrailer],
            [
                //'trailer_num' => $request->inputidtrailer,
                'status' => now(),
                'pallets_on_trailer' => $request->inputpallets ?? 0,
                'pallets_on_floor' => null,
                'carrier' => $request->inputshipmentcarrier,
                'gnct_id_availability_indicator' => null,
                //'location' => $request->inputorigin,
                'date_in' => now(),
                'username' => Auth::check() ? Auth::user()->username : 'system',
                'availability' => 'Used',
                'date_out' => now(), // Establece la fecha y hora actual
                'transaction_date' => now() // También aquí
            ]
        );

        // Si el trailer ya existía, actualizar los valores
        if (!$trailer->wasRecentlyCreated) {
            $trailer->update([
                'status' => now(),
                'pallets_on_trailer' => $request->inputpallets ?? 0,
                'carrier' => $request->inputshipmentcarrier,
                //'location' => $request->inputorigin,
                'username' => Auth::check() ? Auth::user()->username : 'system',
                'availability' => 'Used',
                'date_out' => now(),
                'transaction_date' => now(),
            ]);
        }

        // Obtener la fecha y hora actual
        $currentDateTime = now(); // Usa `now()` para obtener la fecha y hora actuales en Laravel

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
            'id_trailer' => $trailer->trailer_num,
            'id_company' => $request->inputshipmentcarrier,
            'trailer' => $request->inputshipmenttrailer,
            'truck' => $request->inputshipmenttruck,
            'id_driver' => $request->inputshipmentdriver,
            'etd' => $estimateddateofdeparture,
            'units' => $request->inputshipmentsunits,
            'pallets' => $request->inputpallets,
            'seal1' => $request->inputshipmentsecurityseals,
            'seal2' => $request->inputshipmentsecurityseals2,
            'notes' => $request->inputshipmentnotes,
            'security_company_id' => $request->inputshipmentoverhaulid,
            'tracker1' => $request->inputshipmentdevicenumber,
            'tracker2' => $request->tracker2,
            'tracker3' => $request->tracker3,
            'gnct_id_current_status' => $request->inputshipmentcurrentstatus,
            'lane' => $request->ln_code,

            // Asignar la fecha y hora actual solo si el parámetro `inputshipmentdriver` no está vacío
            'driver_assigned_date' => $request->inputshipmentdriver ? $currentDateTime : null,

        ]);

        // Actualizar la tabla `empty_trailer` para el trailer correspondiente
        /*EmptyTrailer::where('trailer_num', $request->inputidtrailer)
        ->update([
            'availability' => 'Used',
            'date_out' => now(), // Establece la fecha y hora actual
            'transaction_date' => now() // También aquí
        ]);*/

        // Redirigir con mensaje de éxito
        return redirect()->route('workflowtrafficstart')->with('success', 'Shipment successfully added!');
    }

    public function indexwhapptapproval(){
        if (Auth::check()) {
            $shipments = Shipments::with(['shipmenttype', 'currentstatus', 'origin', 'destinations', 'carrier', 'emptytrailer', 'services', 'driverowner', 'drivers'])
            /*->whereHas('destinations', function ($query) {
                $query->where('fac_auth', 1); // Filtrar las relaciones destinations con fac_auth = 1
            })*/
            ->whereNull('wh_auth_date')
            ->whereHas('destinations', function ($query) {
                $query->join('facilities', 'companies.CoName', '=', 'facilities.fac_name')
                      ->where('facilities.fac_auth', 1);
            })
            ->get();

            return view('home.whapptapproval', compact('shipments'));
            //return view('home.whapptapproval');
        }
        return redirect('/login');
    }

    //Funcion actualizar EmptyTrailers
    public function whetaapproval(Request $request){
        // Validar los datos
        $validated = $request->validate([
            'pk_shipment' => 'required',
            //'id_trailer' => 'required',
            //'stm_id' =>  'nullable',
            'pallets' => 'required|min:1|integer',
            'units' => 'required|min:1|integer',
            //'etd' => 'required',
            'wh_auth_date' => 'required',
            'door_number' => 'nullable',
        ], [
            //'id_trailer.required' => 'ID Trailer is required.',
            //'trailer_num.string' => 'El campo ID Trailer debe ser una cadena de texto.',
            //'trailer_num.max' => 'El campo ID Trailer no puede exceder los 50 caracteres.',
            //'stm_id.required' => 'La fecha de estatus es obligatoria.',
            //'stm_id.date' => 'El campo de fecha de estatus debe ser una fecha válida.',
            'pallets.required' => 'Pallets are required.',
            //'pallets_on_trailer.string' => 'El campo Pallets on Trailer debe ser una cadena de texto.',
            //'pallets_on_trailer.max' => 'El campo Pallets on Trailer no puede exceder los 50 caracteres.',
            //'pallets_on_floor.required' => 'El campo Pallets on Floor es obligatorio.',
            'units.required' => 'Units are rquired.',
            //'carrier.string' => 'El campo Carrier debe ser una cadena de texto.',
            //'carrier.max' => 'El campo Carrier no puede exceder los 50 caracteres.',
            //'etd.required' => 'ETD is required.',

            'wh_auth_date.required' => 'WH Auth Date is required.',
        ]);

        // Buscar el trailer
        $shipment = Shipments::findOrFail($validated['pk_shipment']);

        // Convertir las fechas al formato adecuado
        /*$validated['status'] = $validated['status']
            ? Carbon::createFromFormat('m/d/Y', $validated['status'])->format('Y-m-d')
            : null;*/
        /*$validated['etd'] = $validated['etd']
            ? Carbon::createFromFormat('m/d/Y H:i:s', $validated['etd'])->format('Y-m-d H:i:s')
            : null;*/
        $validated['wh_auth_date'] = $validated['wh_auth_date']
            ? Carbon::createFromFormat('m/d/Y H:i:s', $validated['wh_auth_date'])->format('Y-m-d H:i:s')
            : null;
        /*$validated['transaction_date'] = $validated['transaction_date']
            ? Carbon::createFromFormat('m/d/Y H:i:s', $validated['transaction_date'])->format('Y-m-d H:i:s')
            : null;*/

            // Log para depuración
        Log::info('Received data:', $validated);

        // Actualizar los campos
        $shipment->update($validated);

        return response()->json(['message' => 'Successfully WH ETA Approval saved succesfully'], 200);
    }

    public function getShipmentswh(Request $request){
        $query = Shipments::with(['shipmenttype', 'currentstatus', 'origin', 'destinations', 'carrier', 'emptytrailer', 'services', 'driverowner', 'drivers'])
            // Aplicar filtro de 'fac_auth' directamente
            /*->whereHas('destinations', function ($query) {
                    $query->where('fac_auth', 1); // Filtrar las relaciones destinations con fac_auth = 1
            })*/
            ->whereNull('wh_auth_date')
            ->whereHas('destinations', function ($query) {
                $query->join('facilities', 'companies.CoName', '=', 'facilities.fac_name')
                      ->where('facilities.fac_auth', 1);
            });

        // Filtros generales (searchemptytrailergeneral)
        if ($request->has('searchwh')) {
            $search = $request->input('searchwh');
            //$formattedDate = null;
            $formattedDateTime = null;
            //$formattedDate = \DateTime::createFromFormat('m/d/Y', $search);
            $formattedDateTime = \DateTime::createFromFormat('m/d/Y H:i:s',$search);

            //$finalstatus = $date->format('Y-m-d');
            $query->where(function($q) use ($search, $formattedDateTime) {
                $fields = ['stm_id', 'units', 'pallets'];
                foreach ($fields as $field) {
                    $q->orWhere($field, 'like', "%$search%");
                }
                //$q->where('stm_id', 'like', "%$search%")
                //->orWhereNull('stm_id')
                $q->orWhereHas('shipmenttype', function($q) use ($search) {
                    $q->where('gntc_description', 'like', "%$search%");
                })
                //->orWhereNull('shipmenttype')
                //->orWhere('secondary_shipment_id','like',"%$search%")
                //->orWhereNull('secondary_shipment_id')
                //->orWhere('id_trailer','like',"%$search%")
                //->orWhereNull('id_trailer')

                ->orWhere('etd', 'like', $formattedDateTime);
                //->orWhereNull('etd')

                //->orWhere('units','like',"%$search%")
                //->orWhereNull('units')
                //->orWhere('pallets', 'like', "%$search%");
                //->orWhereNull('pallets')

                /*->orWhere('driver_assigned_date', 'like', $formattedDateTime)
                ->orWhereNull('driver_assigned_date')
                ->orWhere('pick_up_date', 'like', $formattedDateTime)
                ->orWhereNull('pick_up_date')
                ->orWhere('intransit_date', 'like', $formattedDateTime)
                ->orWhereNull('intransit_date')
                ->orWhere('delivered_date', 'like', $formattedDateTime)
                //->orWhereNull('delivered_date')
                ->orWhere('secured_yarddate', 'like', $formattedDateTime)
                //->orWhereNull('secured_yarddate')
                ->orWhere('wh_auth_date', 'like', $formattedDateTime)
                //->orWhereNull('wh_auth_date')

                ->orWhere('offloading_time', 'like', "%$search%")
                //->orWhereNull('offlanding_time')

                ->orWhere('billing_date', 'like', $formattedDateTime)
                //->orWhereNull('billing_date')

                ->orWhere('billing_id','like',"%$search%")
                //->orWhereNull('billing_id')
                ->orWhere('device_number','like',"%$search%");*/
                //->orWhereNull('device_number');
            });
        }


        //Filtros específicos de parámetros (inputs de filtros aplicados)
        if ($request->has('stm_id') && $request->input('stm_id') != '') {
            $query->where('stm_id', 'like', "%{$request->input('stm_id')}%");
        }
        /*if ($request->has('gnct_id_shipment_type') && $request->input('gnct_id_shipment_type') != '') {
            $query->whereHas('shipmenttype', function($q) use ($request) {
                $q->where('gnct_id', $request->input('gnct_id_shipment_type'));
            });
        }*/
        // Filtro para múltiples ubicaciones seleccionadas
        if ($request->has('shipment_types') && $request->input('shipment_types') != '') {
            $ship = explode(',', $request->input('shipment_types')); // Convierte la cadena en un array

            $query->whereHas('shipmenttype', function($q) use ($ship) {
                $q->whereIn('gnct_id', $ship); // Filtra por cualquier ubicación en el array
            });
        }
        if ($request->has('secondary_shipment_id') && $request->input('secondary_shipment_id') != '') {
            $query->where('secondary_shipment_id', 'like', "%{$request->input('secondary_shipment_id')}%");
        }
        if ($request->has('id_trailer') && $request->input('id_trailer') != '') {
            $query->where('id_trailer', 'like', "%{$request->input('id_trailer')}%");
        }
        if ($request->has('etd_start') && $request->has('etd_end') &&
            $request->input('etd_start') != '' && $request->input('etd_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('etd_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('etd_end'));
            $query->whereBetween('etd', [
                $startDate,
                $endDate
            ]);
        }
        if ($request->has('units') && $request->input('units') != '') {
            $query->where('units', 'like', "%{$request->input('units')}%");
        }
        if ($request->has('pallets') && $request->input('pallets') != '') {
            $query->where('pallets', 'like', "%{$request->input('pallets')}%");
        }
        if ($request->has('driver_assigned_date_start') && $request->has('driver_assigned_date_end') &&
            $request->input('driver_assigned_date_start') != '' && $request->input('driver_assigned_date_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('driver_assigned_date_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('driver_assigned_date_end'));
            $query->whereBetween('driver_assigned_date', [
                $startDate,
                $endDate
            ]);
        }
        if ($request->has('pick_up_date_start') && $request->has('pick_up_date_end') &&
            $request->input('pick_up_date_start') != '' && $request->input('pick_up_date_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('pick_up_date_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('pick_up_date_end'));
            $query->whereBetween('pick_up_date', [
                $startDate,
                $endDate
            ]);
        }
        if ($request->has('intransit_date_start') && $request->has('intransit_date_end') &&
            $request->input('intransit_date_start') != '' && $request->input('intransit_date_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('intransit_date_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('intransit_date_end'));
            $query->whereBetween('intransit_date', [
                $startDate,
                $endDate
            ]);
        }
        if ($request->has('delivered_date_start') && $request->has('delivered_date_end') &&
            $request->input('delivered_date_start') != '' && $request->input('delivered_date_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('delivered_date_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('delivered_date_end'));
            $query->whereBetween('delivered_date', [
                $startDate,
                $endDate
            ]);
        }
        if ($request->has('secured_yarddate_start') && $request->has('secured_yarddate_end') &&
            $request->input('secured_yarddate_start') != '' && $request->input('secured_yarddate_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('secured_yarddate_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('secured_yarddate_end'));
            $query->whereBetween('secured_yarddate', [
                $startDate,
                $endDate
            ]);
        }
        if ($request->has('wh_auth_date_start') && $request->has('wh_auth_date_end') &&
            $request->input('wh_auth_date_start') != '' && $request->input('wh_auth_date_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('wh_auth_date_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('wh_auth_date_end'));
            $query->whereBetween('wh_auth_date', [
                $startDate,
                $endDate
            ]);
        }

        if ($request->has('offlanding_time_start') && $request->has('offlanding_time_end') &&
            $request->input('offlanding_time_start') != '' && $request->input('offlanding_time_end') != '') {

                //$startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('offlanding_time_start'));
                //$endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('offlanding_time_end'));
            $query->whereBetween('offloading_time', [
                $request->input('offlanding_time_start'),
                $request->input('offlanding_time_end')

            ]);
        }

        if ($request->has('billing_date_start') && $request->has('billing_date_end') &&
            $request->input('billing_date_start') != '' && $request->input('billing_date_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('billing_date_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('billing_date_end'));
            $query->whereBetween('billing_date', [
                $startDate,
                $endDate
            ]);
        }

        if ($request->has('billing_id') && $request->input('billing_id') != '') {
            $query->where('billing_id', 'like', "%{$request->input('billing_id')}%");
        }
        if ($request->has('device_number') && $request->input('device_number') != '') {
            $query->where('device_number', 'like', "%{$request->input('device_number')}%");
        }


        // Obtener los trailers con los filtros aplicados
        $shipments = $query->get();

        // Devolver los datos en formato JSON
        return response()->json($shipments);
    }

    /*public function getService(Request $request)
    {
        $id_service = $request->id_service;
        
        $service = Cache::remember("service_{$id_service}", 60, function () use ($id_service) {
            return DB::table('services')
                ->where('id_service', $id_service)
                ->select('from', 'to')
                ->first();
        });

        if ($service) {
            return response()->json([
                'success' => true,
                'from' => $service->from,
                'to' => $service->to
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }*/

    public function getService(Request $request)
{
    $id_service = $request->id_service;

    // Cachear la consulta de 'services'
    $service = Cache::remember("service_{$id_service}", 60, function () use ($id_service) {
        return DB::table('services')
            ->where('id_service', $id_service)
            ->select('from', 'to')
            ->first();
    });

    if (!$service) {
        return response()->json(['success' => false, 'message' => 'Service not found']);
    }

    // Buscar en 'companies' los valores de 'CoName' y 'pk_company' con los IDs obtenidos
    $fromCompany = DB::table('companies')
        ->where('CoName', $service->from)
        ->select('CoName', 'pk_company')
        ->first();

    $toCompany = DB::table('companies')
        ->where('CoName', $service->to)
        ->select('CoName', 'pk_company')
        ->first();

    return response()->json([
        'success' => true,
        'from' => $fromCompany ? $fromCompany->CoName : null,
        'from_id' => $fromCompany ? $fromCompany->pk_company : null,
        'to' => $toCompany ? $toCompany->CoName : null,
        'to_id' => $toCompany ? $toCompany->pk_company : null
    ]);
}

    public function getLanesTrafficWorkflowStart(Request $request){
        $id_companie = $request->id_companie;

        // Usamos Cache para almacenar resultados y evitar consultas repetidas
        $laneCode = DB::table('lanes')
                ->where('ln_origin', $id_companie)
                ->select("ln_code")
                ->first();

        if ($laneCode) {
            return response()->json([
                'success' => true,
                'ln_code' => $laneCode ? $laneCode->ln_code :null
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }


}
