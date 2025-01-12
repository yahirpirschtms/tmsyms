<?php

namespace App\Http\Controllers;

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

    public function createWorkflowStartWithEmptyTrailer(Request $request)
{
    if (Auth::check()) {
        // Recuperar los datos de la URL o de la sesión
        $origin = $request->query('origin', session('shipment_origin'));
        $trailerId = $request->query('trailerId', session('trailer_id'));

        // Verificar si la solicitud proviene del botón (usamos una bandera)
        $from_button = $request->query('from_button', 1); // 1 si viene del botón, 0 si no

        // Pasar los datos a la vista
        return view('home.trafficworkflowstart', compact('origin', 'trailerId', 'from_button'));
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

            return view('home.trafficworkflowstart', compact('origin', 'trailerId', 'from_button'));
        }

        return redirect('/login');
    }

}
