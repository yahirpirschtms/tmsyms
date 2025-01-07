<?php

namespace App\Http\Controllers;

use App\Models\EmptyTrailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    //Eliminar el empty trailer
    public function destroy($id)
    {
        // Buscar el trailer por su ID
        $trailer = EmptyTrailer::find($id);

        // Si no se encuentra el trailer, retornar error
        if (!$trailer) {
            return response()->json(['error' => 'Trailer not found'], 404);
        }

        // Eliminar el trailer
        $trailer->delete();

        // Retornar una respuesta de éxito
        return response()->json(['success' => 'Trailer deleted successfully']);
    }


    //Funcion para traer todos los empty trailers registrados 
    public function getEmptyTrailers()
    {
        return response()->json(EmptyTrailer::all());
    }
    
    //Funcion Validar input dinamicamente
    public function validateField(Request $request)
    {
        $field = $request->get('field'); // Campo a validar
        $value = $request->get('value'); // Valor del campo

        $rules = [
            //'inputidtrailer' => 'required|integer|unique:empty_trailer,pk_trailer|max:50',
            'inputidtrailer' => 'required|string|min:3',
            'inputdateofstatus' => 'required|date',
            'inputpalletsontrailer' => 'required|int|min:4',
            'inputpalletsonfloor' => 'required|string|max:50',
            'inputcarrier' => 'required|string|max:50',
            'inputavailabilityindicator' => 'required|integer|exists:generic_catalogs,gnct_id',
            'inputlocation' => 'required|string|exists:companies,id_company',
            'inputdatein' => 'required|date',
            'inputdateout' => 'required|date',
            'inputtransactiondate' => 'required|date',
            'inputusername' => 'required|string|max:50',
        ];

        $validator = Validator::make([$field => $value], [$field => $rules[$field] ?? '']);

        if ($validator->fails()) {
            return response()->json(['valid' => false, 'message' => $validator->errors()->first($field)]);
        }

        return response()->json(['valid' => true]);
    }

    public function index(){
        if(Auth::check()){
            $emptyTrailers = EmptyTrailer::all(); // Obtén todos los registros
            return view('home.index', compact('emptyTrailers'));
        }
        return redirect('/login');
    }
    public function store(Request $request)
    {
        // Validaciones
        $validated = $request->validate([
            //'inputidtrailer' => 'required|integer|unique:empty_trailer,pk_trailer',
            'inputidtrailer' => 'required|string|max:50',
            'inputdateofstatus' => 'required|date',
            'inputpalletsontrailer' => 'required|string|min:2',
            'inputpalletsonfloor' => 'required|string|max:50',
            'inputcarrier' => 'required|string|max:50',
            'inputavailabilityindicator' => 'required|integer|exists:generic_catalogs,gnct_id',
            'inputlocation' => 'required|string|exists:companies,id_company',
            'inputdatein' => 'required|date',
            'inputdateout' => 'required|date',
            'inputtransactiondate' => 'required|date',
            'inputusername' => 'required|string|max:50',
        ]);

        // Crear un nuevo registro
        EmptyTrailer::create([
            'pk_trailer' => $request->inputidtrailer,
            'trailer_num' => $request->inputidtrailer,
            'status' => $request->inputdateofstatus,
            'pallets_on_trailer' => $request->inputpalletsontrailer,
            'pallets_on_floor' => $request->inputpalletsonfloor,
            'carrier' => $request->inputcarrier,
            'gnct_id_avaibility_indicator' => $request->inputavailabilityindicator,
            'location' => $request->inputlocation,
            'date_in' => $request->inputdatein,
            'date_out' => $request->inputdateout,
            'transaction_date' => $request->inputtransactiondate,
            'username' => $request->inputusername,
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('emptytrailer')->with('success', 'Trailer successfully added!');

    }
}
