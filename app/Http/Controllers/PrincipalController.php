<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    //

    public function index(){

        $token = session('token');

        return view('principal.principal', compact('token'));
    }
}
