<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluacionesController extends Controller
{
    //
    public function index(){
        return view('evaluaciones.index');
    }
}
