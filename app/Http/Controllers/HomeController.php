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
    public function update(Request $request){
        // Validar los datos
        $validated = $request->validate([
            'pk_trailer' => 'required',
            'trailer_num' => 'required|string|max:50',
            'status' =>  'nullable|date',
            'pallets_on_trailer' => 'required|string|max:50',
            'pallets_on_floor' => 'required|string|max:50',
            'carrier' => 'required|string|max:50',
            'gnct_id_avaibility_indicator' => 'required|integer',
            'location' => 'required|string',
            'date_in' => 'required|date',
            'date_out' => 'required|date',
            'transaction_date' => 'required|date',
            'username' => 'required|string|max:50',
        ], [
            'trailer_num.required' => 'El ID Trailer es obligatorio.',
            'trailer_num.string' => 'El campo ID Trailer debe ser una cadena de texto.',
            'trailer_num.max' => 'El campo ID Trailer no puede exceder los 50 caracteres.',
            'status.required' => 'La fecha de estatus es obligatoria.',
            'status.date' => 'El campo de fecha de estatus debe ser una fecha válida.',
            'pallets_on_trailer.required' => 'El campo Pallets on Trailer es obligatorio.',
            'pallets_on_trailer.string' => 'El campo Pallets on Trailer debe ser una cadena de texto.',
            'pallets_on_trailer.max' => 'El campo Pallets on Trailer no puede exceder los 50 caracteres.',
            'pallets_on_floor.required' => 'El campo Pallets on Floor es obligatorio.',
            'pallets_on_floor.string' => 'El campo Pallets on Floor debe ser una cadena de texto.',
            'pallets_on_floor.max' => 'El campo Pallets on Floor no puede exceder los 50 caracteres.',
            'carrier.required' => 'El campo Carrier es obligatorio.',
            'carrier.string' => 'El campo Carrier debe ser una cadena de texto.',
            'carrier.max' => 'El campo Carrier no puede exceder los 50 caracteres.',
            'gnct_id_avaibility_indicator.required' => 'El campo Availability Indicator es obligatorio.',
            'gnct_id_avaibility_indicator.integer' => 'El campo Availability Indicator debe ser un número entero.',
            'location.required' => 'El campo Location es obligatorio.',
            'location.string' => 'El campo Location debe ser una cadena de texto.',
            'date_in.required' => 'El campo Date In es obligatorio.',
            'date_out.required' => 'El campo Date Out es obligatorio.',
            'transaction_date.required' => 'El campo Transaction Date es obligatorio.',
            'username.required' => 'El campo Username es obligatorio.',
            'username.string' => 'El campo Username debe ser una cadena de texto.',
            'username.max' => 'El campo Username no puede exceder los 50 caracteres.',
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
    $validated['date_out'] = $validated['date_out'] 
        ? Carbon::createFromFormat('m/d/Y H:i:s', $validated['date_out'])->format('Y-m-d H:i:s') 
        : null;
    $validated['transaction_date'] = $validated['transaction_date'] 
        ? Carbon::createFromFormat('m/d/Y H:i:s', $validated['transaction_date'])->format('Y-m-d H:i:s') 
        : null;

        // Log para depuración
    Log::info('Datos recibidos:', $validated);

    // Actualizar los campos
    $trailer->update($validated);

    return response()->json(['message' => 'Trailer actualizado con éxito'], 200);
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

        return response()->json(['message' => 'Trailer eliminado exitosamente'], 200);
    }

    //Funcion para traer todos los empty trailers registrados 
    public function getEmptyTrailers(){
        // Obtiene los trailers con las relaciones
        $emptyTrailers = EmptyTrailer::with(['availabilityIndicator', 'locations'])->get();
        
        // Devuelve la respuesta en formato JSON
        return response()->json($emptyTrailers);
    }
        
    public function index(){
        if (Auth::check()) {
            $emptyTrailers = EmptyTrailer::with(['availabilityIndicator', 'locations'])->get();
            //$emptyTrailers = EmptyTrailer::all(); // Obtén todos los registros
            //dd($emptyTrailers);
            return view('home.index', compact('emptyTrailers'));
        }
        return redirect('/login');
    }

    public function store(Request $request)
    {
        // Validaciones
        $validated = $request->validate([
            'inputidtrailer' => 'required|string|max:50',
            'inputdateofstatus' => 'required|date',
            'inputpalletsontrailer' => 'required|string|max:50',
            'inputpalletsonfloor' => 'required|string|max:50',
            'inputcarrier' => 'required|string|max:50',
            'inputavailabilityindicator' => 'required|integer|exists:generic_catalogs,gnct_id',
            'inputlocation' => 'required|string|exists:companies,id_company',
            'inputdatein' => 'required|date',
            'inputdateout' => 'required|date',
            'inputtransactiondate' => 'required|date',
            'inputusername' => 'required|string|max:50',
        ], [
            'inputidtrailer.required' => 'El ID Trailer es obligatorio.',
            'inputidtrailer.string' => 'El campo ID Trailer debe ser una cadena de texto.',
            'inputidtrailer.max' => 'El campo ID Trailer no puede exceder los 50 caracteres.',
            'inputdateofstatus.required' => 'La fecha de estatus es obligatoria.',
            'inputdateofstatus.date' => 'El campo de fecha de estatus debe ser una fecha válida.',
            'inputpalletsontrailer.required' => 'El campo Pallets on Trailer es obligatorio.',
            'inputpalletsontrailer.string' => 'El campo Pallets on Trailer debe ser una cadena de texto.',
            'inputpalletsontrailer.max' => 'El campo Pallets on Trailer no puede exceder los 50 caracteres.',
            'inputpalletsonfloor.required' => 'El campo Pallets on Floor es obligatorio.',
            'inputpalletsonfloor.string' => 'El campo Pallets on Floor debe ser una cadena de texto.',
            'inputpalletsonfloor.max' => 'El campo Pallets on Floor no puede exceder los 50 caracteres.',
            'inputcarrier.required' => 'El campo Carrier es obligatorio.',
            'inputcarrier.string' => 'El campo Carrier debe ser una cadena de texto.',
            'inputcarrier.max' => 'El campo Carrier no puede exceder los 50 caracteres.',
            'inputavailabilityindicator.required' => 'El campo Availability Indicator es obligatorio.',
            'inputavailabilityindicator.integer' => 'El campo Availability Indicator debe ser un número entero.',
            'inputavailabilityindicator.exists' => 'El Availability Indicator seleccionado no es válido.',
            'inputlocation.required' => 'El campo Location es obligatorio.',
            'inputlocation.string' => 'El campo Location debe ser una cadena de texto.',
            'inputlocation.exists' => 'La Location seleccionada no es válida.',
            'inputdatein.required' => 'El campo Date In es obligatorio.',
            'inputdateout.required' => 'El campo Date Out es obligatorio.',
            'inputtransactiondate.required' => 'El campo Transaction Date es obligatorio.',
            'inputusername.required' => 'El campo Username es obligatorio.',
            'inputusername.string' => 'El campo Username debe ser una cadena de texto.',
            'inputusername.max' => 'El campo Username no puede exceder los 50 caracteres.',
        ]);
    
        // Convertir las fechas al formato 'm/d/Y'
        $dateOfStatus = Carbon::createFromFormat('m/d/Y', $request->inputdateofstatus)->format('Y-m-d'); // Solo fecha
        $dateIn = Carbon::createFromFormat('m/d/y H:i:s', $request->inputdatein)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos
        $dateOut = Carbon::createFromFormat('m/d/y H:i:s', $request->inputdateout)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos
        $transactionDate = Carbon::createFromFormat('m/d/y H:i:s', $request->inputtransactiondate)->format('Y-m-d H:i:s'); // Fecha y hora con minutos y segundos

    
        // Crear un nuevo registro
        EmptyTrailer::create([
            //'pk_trailer' => $request->inputidtrailer,
            'trailer_num' => $request->inputidtrailer,
            'status' => $dateOfStatus,
            'pallets_on_trailer' => $request->inputpalletsontrailer,
            'pallets_on_floor' => $request->inputpalletsonfloor,
            'carrier' => $request->inputcarrier,
            'gnct_id_avaibility_indicator' => $request->inputavailabilityindicator,
            'location' => $request->inputlocation,
            'date_in' => $dateIn,
            'date_out' => $dateOut,
            'transaction_date' => $transactionDate,
            'username' => $request->inputusername,
        ]);
    
        // Redirigir con mensaje de éxito
        return redirect()->route('emptytrailer')->with('success', 'Trailer successfully added!');
    }
    
}
