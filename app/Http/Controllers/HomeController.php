<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\EmptyTrailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    //Funcion actualizar EmptyTrailers
    /*public function update(Request $request){
        // Validar los datos
        $validated = $request->validate([
            'pk_trailer' => 'required',
            'trailer_num' => 'required|unique:empty_trailer,trailer_num',
            'status' =>  'required|date',
            'pallets_on_trailer' => 'nullable',
            'pallets_on_floor' => 'nullable',
            'carrier' => 'required|exists:companies,pk_company',
            'gnct_id_availability_indicator' => 'nullable|exists:generic_catalogs,gnct_id',
            'location' => 'required|exists:companies,pk_company',
            'date_in' => 'required|date',
            //'date_out' => 'required|date',
            //'transaction_date' => 'required|date',
            'username' => 'nullable',
        ], [
            'trailer_num.required' => 'ID Trailer is required.',
            'trailer_num.unique' => 'The trailer number has already been taken.',
            //'trailer_num.max' => 'El campo ID Trailer no puede exceder los 50 caracteres.',
            'status.required' => 'Status date is required.',
            'status.date' => 'The status date field must be a valid date.',
            //'pallets_on_trailer.required' => 'El campo Pallets on Trailer es obligatorio.',
            //'pallets_on_trailer.string' => 'El campo Pallets on Trailer debe ser una cadena de texto.',
            //'pallets_on_trailer.max' => 'El campo Pallets on Trailer no puede exceder los 50 caracteres.',
            //'pallets_on_floor.required' => 'El campo Pallets on Floor es obligatorio.',
            //'pallets_on_floor.string' => 'El campo Pallets on Floor debe ser una cadena de texto.',
            //'pallets_on_floor.max' => 'El campo Pallets on Floor no puede exceder los 50 caracteres.',
            'carrier.required' => 'Carrier is required.',
            'carrier.exists' => 'Carrier selected is not valid.',
            //'carrier.max' => 'El campo Carrier no puede exceder los 50 caracteres.',
            //'gnct_id_avaibility_indicator.required' => 'El campo Availability Indicator es obligatorio.',
            'gnct_id_availability_indicator.exists' => 'Availability Indicator selected is not valid.',
            'location.required' => 'Location is required.',
            'location.exists' => 'Location selected is not valid.',
            'date_in.required' => 'Date In is required.',
            //'date_out.required' => 'El campo Date Out es obligatorio.',
            //'transaction_date.required' => 'El campo Transaction Date es obligatorio.',
            //'username.required' => 'El campo Username es obligatorio.',
            //'username.string' => 'El campo Username debe ser una cadena de texto.',
            //'username.max' => 'El campo Username no puede exceder los 50 caracteres.',
        ]);
    
        // Buscar el trailer
        $trailer = EmptyTrailer::findOrFail($validated['pk_trailer']);

        // Convertir las fechas al formato adecuado
        $validated['status'] = $validated['status']
            ? Carbon::createFromFormat('m/d/Y', $validated['status'])->format('Y-m-d') 
            : null;
        $validated['date_in'] = $validated['date_in'] 
            ? Carbon::createFromFormat('m/d/Y H:i:s', $validated['date_in'])->format('Y-m-d H:i:s') 
            : null;
        //$validated['date_out'] = $validated['date_out'] 
        //    ? Carbon::createFromFormat('m/d/Y H:i:s', $validated['date_out'])->format('Y-m-d H:i:s') 
        //    : null;
        //$validated['transaction_date'] = $validated['transaction_date'] 
        //    ? Carbon::createFromFormat('m/d/Y H:i:s', $validated['transaction_date'])->format('Y-m-d H:i:s') 
        //    : null;

            // Log para depuración
        Log::info('Received data:', $validated);

        // Actualizar los campos
        $trailer->update($validated);

        return response()->json(['message' => 'Successfully updated trailer'], 200);
    }*/
    /*public function update(Request $request)
{
    try {
        // Validar los datos
        $validated = $request->validate([
            'pk_trailer' => 'required',
            'trailer_num' => 'required|unique:empty_trailer,trailer_num',
            'status' => 'required|date',
            'pallets_on_trailer' => 'nullable',
            'pallets_on_floor' => 'nullable',
            'carrier' => 'required|exists:companies,pk_company',
            'gnct_id_availability_indicator' => 'nullable|exists:generic_catalogs,gnct_id',
            'location' => 'required|exists:companies,pk_company',
            'date_in' => 'required|date',
        ], [
            'trailer_num.required' => 'ID Trailer is required.',
            'trailer_num.unique' => 'The trailer number has already been taken.',
            'status.required' => 'Status date is required.',
            'status.date' => 'The status date field must be a valid date.',
            'carrier.required' => 'Carrier is required.',
            'carrier.exists' => 'Carrier selected is not valid.',
            'location.required' => 'Location is required.',
            'location.exists' => 'Location selected is not valid.',
            'date_in.required' => 'Date In is required.',
        ]);

        // Buscar el trailer
        $trailer = EmptyTrailer::findOrFail($validated['pk_trailer']);

        // Convertir las fechas al formato adecuado
        $validated['status'] = Carbon::createFromFormat('m/d/Y', $validated['status'])->format('Y-m-d');
        $validated['date_in'] = Carbon::createFromFormat('m/d/Y H:i:s', $validated['date_in'])->format('Y-m-d H:i:s');

        // Actualizar los campos
        $trailer->update($validated);

        return response()->json(['message' => 'Successfully updated trailer'], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Devolver errores de validación como JSON
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An unexpected error occurred.'], 500);
    }
}*/
public function update(Request $request)
{
    try {
        // Validar los datos enviados
        $validated = $request->validate([
            'updateinputpktrailer' => 'required', // pk_trailer
            //'updateinputidtrailer' => 'required|unique:empty_trailer,trailer_num',
            'updateinputdateofstatus' => 'required|date', // status
            'updateinputpalletsontrailer' => 'required|numeric|min:1', // pallets_on_trailer
            'updateinputpalletsonfloor' => 'required|numeric|min:1', // pallets_on_floor
            'updateinputcarrier' => 'required', // carrier
            'updateinputavailabilityindicator' => 'nullable|exists:generic_catalogs,gnct_id', // gnct_id_availability_indicator
            'updateinputlocation' => 'nullable', // location
            'updateinputdatein' => 'required|date', // date_in
        ], [
            //'updateinputidtrailer.required' => 'ID Trailer is required.',
            //'updateinputidtrailer.unique' => 'The trailer number has already been taken.',
            'updateinputdateofstatus.required' => 'Status date is required.',
            'updateinputdateofstatus.date' => 'The status date field must be a valid date.',
            'updateinputcarrier.required' => 'Carrier is required.',
            //'updateinputcarrier.exists' => 'Carrier selected is not valid.',
            //'updateinputlocation.required' => 'Location is required.',
            //'updateinputlocation.exists' => 'Location selected is not valid.',
            'updateinputdatein.required' => 'Date In is required.',
            'updateinputpalletsontrailer.numeric' => 'Pallets on trailer must be an integer.',
            'updateinputpalletsonfloor.numeric' => 'Pallets on floor must be an integer.',
            'updateinputpalletsontrailer.required' => 'Pallets on trailer are required.',
            'updateinputpalletsonfloor.required' => 'Pallets on floor are required.',
            'updateinputpalletsontrailer.min' => 'Pallets on trailer must have a valid value.',
            'updateinputpalletsonfloor.min' => 'Pallets on floor must have a valid value.',
        ]);

        // Buscar el trailer
        $trailer = EmptyTrailer::findOrFail($validated['updateinputpktrailer']);

        // Mapear los datos del formulario a los nombres de las columnas
        $dataToUpdate = [
            //'trailer_num' => $validated['updateinputidtrailer'],
            'status' => Carbon::createFromFormat('m/d/Y', $validated['updateinputdateofstatus'])->format('Y-m-d'),
            'pallets_on_trailer' => $validated['updateinputpalletsontrailer'],
            'pallets_on_floor' => $validated['updateinputpalletsonfloor'],
            'carrier' => $validated['updateinputcarrier'],
            'gnct_id_availability_indicator' => $validated['updateinputavailabilityindicator'],
            //'location' => $validated['updateinputlocation'],
            'date_in' => Carbon::createFromFormat('m/d/Y H:i:s', $validated['updateinputdatein'])->format('Y-m-d H:i:s'),
        ];

        // Actualizar el trailer
        $trailer->update($dataToUpdate);

        return response()->json(['message' => 'Successfully updated trailer'], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Devolver errores de validación como JSON
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An unexpected error occurred.'], 500);
    }
}



    //Funcion eliminar trailers
    public function destroy($id){
        // Buscar el tráiler por ID
        $trailer = EmptyTrailer::find($id);

        if (!$trailer) {
            return response()->json(['message' => 'Trailer not found'], 404);
        }

        // Eliminar el tráiler
        $trailer->delete();

        return response()->json(['message' => 'Trailer successfully removed'], 200);
    }

    //Funcion entrar a la app
    public function index(){
        if (Auth::check()) {
            $emptyTrailers = EmptyTrailer::with(['availabilityIndicator', 'locations', 'carriers'])
            ->whereNull('availability')
            ->orWhere('availability', '')
            ->get();
            return view('home.index', compact('emptyTrailers'));
        }
        return redirect('/login');
    }

    //Funcion Guardar un nuevo empty trailer
    public function store(Request $request)
    {
        // Validaciones
        $validated = $request->validate([

            'inputidtrailer' => 'required|unique:empty_trailer,trailer_num',
            'inputdateofstatus' => 'required|date',
            'inputpalletsontrailer' => 'required|numeric|min:1',
            'inputpalletsonfloor' => 'required|numeric|min:1',
            'inputcarrier' => 'required',
            'inputavailabilityindicator' => 'nullable|exists:generic_catalogs,gnct_id',
            'inputlocation' => 'nullable',
            'inputdatein' => 'required|date',
            'inputdateout' => 'nullable',
            'inputtransactiondate' => 'nullable',
            'inputusername' => 'nullable',
        ], [
            'inputidtrailer.required' => 'ID Trailer is required.',
            'inputidtrailer.unique' => 'The trailer number has already been taken.',
            //'inputidtrailer.string' => 'The Trailer ID field must be a text string.',
            //'inputidtrailer.max' => 'The Trailer ID field cannot exceed 50 characters.',
            'inputdateofstatus.required' => 'Status date is required.',
            'inputdateofstatus.date' => 'The status date field must be a valid date.',
            'inputpalletsontrailer.required' => 'Pallets on trailer are required.',
            //'inputpalletsontrailer.string' => 'The Pallets on Trailer field must be a text string.',
            'inputpalletsontrailer.min' => 'The Pallets On Trailer must have a valid value.',
            'inputpalletsonfloor.required' => 'Pallets on floor are required.',
            //'inputpalletsonfloor.string' => 'The Pallets on Floor field must be a text string.',
            'inputpalletsonfloor.min' => 'The Pallets On Floor must have a valid value.',
            'inputcarrier.required' => 'Carrier is required.',
            //'inputcarrier.exists' => 'Carrier selected is not valid.',
            //'inputcarrier.max' => 'The Carrier field cannot exceed 50 characters.',
            //'inputavailabilityindicator.required' => 'Availability Indicator is required.',
            //'inputavailabilityindicator.integer' => 'Availability Indicator must be an integer.',
            'inputavailabilityindicator.exists' => 'Availability Indicator selected is not valid.',
            //'inputlocation.required' => 'Location is required.',
            //'inputlocation.string' => 'Location must be a text string.',
            //'inputlocation.exists' => 'Location selected is not valid.',
            'inputdatein.required' => 'Date In is required.',
            //'inputdateout.required' => 'Date Out is required.',
            //'inputtransactiondate.required' => 'Transaction Date is required.',
            //'inputusername.required' => 'Username is required.',
            //'inputusername.string' => 'Username must be a text string.',
            //'inputusername.max' => 'Username cannot exceed 50 characters.',
            'inputpalletsontrailer.numeric' => 'Pallets on trailer must be an integer',
            'inputpalletsonfloor.numeric' => 'Pallets on floor must be an integer',

        ]);
    
        // Convertir las fechas al formato 'm/d/Y'
        $dateOfStatus = Carbon::createFromFormat('m/d/Y', $request->inputdateofstatus)->format('Y-m-d'); // Solo fecha
        $dateIn = Carbon::createFromFormat('m/d/Y H:i:s', $request->inputdatein)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos
        //$dateOut = Carbon::createFromFormat('m/d/Y H:i:s', $request->inputdateout)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos
        //$transactionDate = Carbon::createFromFormat('m/d/Y H:i:s', $request->inputtransactiondate)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos

    
        // Crear un nuevo registro
        EmptyTrailer::create([
            //'pk_trailer' => $request->inputidtrailer,
            'trailer_num' => $request->inputidtrailer,
            'status' => $dateOfStatus,
            'pallets_on_trailer' => $request->inputpalletsontrailer,
            'pallets_on_floor' => $request->inputpalletsonfloor,
            'carrier' => $request->inputcarrier,
            'gnct_id_availability_indicator' => $request->inputavailabilityindicator,
            'location' => $request->inputlocation,
            'date_in' => $dateIn,
            'date_out' => $request->inputdateout,
            'transaction_date' => $request->inputtransactiondate,
            'username' => $request->inputusername,
        ]);
    
        // Redirigir con mensaje de éxito
        return redirect()->route('emptytrailer')->with('success', 'Trailer successfully added!');
    }
    
    //Funcion actualizar tabla con los filtros o al refresh
    public function getEmptyTrailers(Request $request){
                $query = EmptyTrailer::with(['availabilityIndicator', 'locations', 'carriers'])
                ->whereNull('availability');                
                
                // Filtros generales (searchemptytrailergeneral)
                if ($request->has('search')) {
                    $search = $request->input('search');
                    $formattedDate = null;
                    $formattedDateTime = null;
                    $formattedDate = \DateTime::createFromFormat('m/d/Y', $search);
                    $formattedDateTime = \DateTime::createFromFormat('m/d/Y H:i:s',$search);
                    
                    //$finalstatus = $date->format('Y-m-d');
                    $query->where(function($q) use ($search, $formattedDate, $formattedDateTime) {
                        $q->where('trailer_num', 'like', "%$search%")
                        ->orWhereDate('status', 'like', $formattedDate)
                        //->orWhereDate('date_in', 'like', $formattedDate)
                        //->orWhereDate('date_out', 'like', $formattedDate)
                        //->orWhereDate('transaction_date', 'like', $formattedDate)
                        ->orWhere('date_in', 'like', $formattedDateTime)
                        //->orWhere('date_out', 'like', $formattedDateTime)
                        //->orWhere('transaction_date', 'like', $formattedDateTime)
                        ->orWhere('pallets_on_trailer', 'like', "%$search%")
                        ->orWhere('pallets_on_floor', 'like', "%$search%")
                        ->orWhereHas('carriers', function($q) use ($search) {
                            $q->where('CoName', 'like', "%$search%");
                        })
                        ->orWhereHas('availabilityIndicator', function($q) use ($search) {
                            $q->where('gntc_description', 'like', "%$search%");
                        })
                        ->orWhereHas('locations', function($q) use ($search) {
                            $q->where('CoName', 'like', "%$search%");
                        })
                        ->orWhere('username', 'like', "%$search%");
                    });
                }

                /// Filtros específicos de parámetros (inputs de filtros aplicados)
        if ($request->has('trailer_num') && $request->input('trailer_num') != '') {
            $query->where('trailer_num', 'like', "%{$request->input('trailer_num')}%");
        }

        // Filtro de fechas para el status
        if ($request->has('status_start') && $request->has('status_end') &&
            $request->input('status_start') != '' && $request->input('status_end') != '') {
            $query->whereBetween('status', [
                Carbon::parse($request->input('status_start'))->startOfDay(),
                Carbon::parse($request->input('status_end'))->endOfDay()
            ]);
        }

        // Otros filtros
        if ($request->has('pallets_on_trailer') && $request->input('pallets_on_trailer') != '') {
            $query->where('pallets_on_trailer', 'like', "%{$request->input('pallets_on_trailer')}%");
        }
        if ($request->has('pallets_on_floor') && $request->input('pallets_on_floor') != '') {
            $query->where('pallets_on_floor', 'like', "%{$request->input('pallets_on_floor')}%");
        }
        if ($request->has('carrier') && $request->input('carrier') != '') {
            $query->whereHas('carriers', function($q) use ($request) {
                $q->where('carrier', $request->input('carrier'));
            });
        }

        // Filtro para múltiples carriers seleccionadas
        if ($request->has('carrierscheckbox') && $request->input('carrierscheckbox') != '') {
            $carrierscheck = explode(',', $request->input('carrierscheckbox')); // Convierte la cadena en un array

            $query->whereHas('carriers', function($q) use ($carrierscheck) {
                $q->whereIn('carrier', $carrierscheck); // Filtra por cualquier ubicación en el array
            });
        }

        if ($request->has('gnct_id_availability_indicator') && $request->input('gnct_id_availability_indicator') != '') {
            $query->whereHas('availabilityIndicator', function($q) use ($request) {
                $q->where('gnct_id', $request->input('gnct_id_availability_indicator'));
            });
        }
        // Filtro para múltiples carriers seleccionadas
        if ($request->has('indicators') && $request->input('indicators') != '') {
            $indicatorscheck = explode(',', $request->input('indicators')); // Convierte la cadena en un array

            $query->whereHas('availabilityIndicator', function($q) use ($indicatorscheck) {
                $q->whereIn('gnct_id', $indicatorscheck); // Filtra por cualquier ubicación en el array
            });
        }

        if ($request->has('location') && $request->input('location') != '') {
            $query->whereHas('locations', function($q) use ($request) {
                $q->where('location', 'like', "%{$request->input('location')}%");
            });
        }
        if ($request->has('username') && $request->input('username') != '') {
            $query->where('username', 'like', "%{$request->input('username')}%");
        }
        
        // Filtro de fechas para date_in
        if ($request->has('date_in_start') && $request->has('date_in_end') &&
            $request->input('date_in_start') != '' && $request->input('date_in_end') != '') {

                $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('date_in_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('date_in_end'));
            $query->whereBetween('date_in', [
                $startDate,
                $endDate
            ]);
        }

        // Filtro para múltiples ubicaciones seleccionadas
        if ($request->has('locations') && $request->input('locations') != '') {
            $locations = explode(',', $request->input('locations')); // Convierte la cadena en un array

            $query->whereHas('locations', function($q) use ($locations) {
                $q->whereIn('location', $locations); // Filtra por cualquier ubicación en el array
            });
        }

        // Filtro de fechas para date_out
        /*if ($request->has('date_out_start') && $request->has('date_out_end') &&
            $request->input('date_out_start') != '' && $request->input('date_out_end') != '') {
            $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('date_out_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('date_out_end'));
            $query->whereBetween('date_out', [
                $startDate,
                $endDate
            ]);
        }*/

        // Filtro de fechas para transaction_date
        /*if ($request->has('transaction_date_start') && $request->has('transaction_date_end') &&
            $request->input('transaction_date_start') != '' && $request->input('transaction_date_end') != '') {
            $startDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('transaction_date_start'));
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s',$request->input('transaction_date_end'));
            $query->whereBetween('transaction_date', [
                $startDate,
                $endDate
            ]);
        }*/
        
        // Obtener los trailers con los filtros aplicados
        $emptyTrailers = $query->get();
        
        // Devolver los datos en formato JSON
        return response()->json($emptyTrailers);
    }

    

}
