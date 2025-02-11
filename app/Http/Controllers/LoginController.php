<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    public function show(){
        if(Auth::check()){
            return redirect('/trafficworkflowstart');
            //return redirect('/home');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request){
        
        $credentials = $request->getCredentials();

        // Busca al usuario por las credenciales
        $user = Auth::getProvider()->retrieveByCredentials(['username' => $credentials['username']]);

        if (!$user) {
            return redirect()->to('/login')->withErrors(['auth.failed' => 'Incorrect username']);
        }        

        // Verifica si la contraseña es texto plano o usa otro algoritmo
        if ($user->password === $credentials['password'] || $user->password === md5($credentials['password'])) {
            // Opcional: Actualiza la contraseña del usuario a Bcrypt para mejorar la seguridad
            $user->password = bcrypt($credentials['password']);
            $user->save();

            // Autentica al usuario
            Auth::login($user);

            return $this->authenticated($request, $user);
        }
        if (Hash::check($credentials['password'], $user->password)) {
            // Si la contraseña coincide, inicia sesión
            Auth::login($user);
        
            return $this->authenticated($request, $user);
        }
            return redirect()->to('/login')->withErrors(['auth.failed' => 'Incorrect password']);
        

        

        /*if(!Auth::validate($credentials)){
            return redirect()->to('/login')->withErrors('auth.failed');
        }

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user);

        return $this->authenticated($request, $user);*/
    }

    public function authenticated(Request $request, $user){
        return redirect('/trafficworkflowstart');
        //return redirect('/home');
    }
}
