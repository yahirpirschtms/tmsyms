<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function index(){
        if(Auth::check()){
            return view('home.index');
        }
        return redirect('/login');
    }
}
