<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    //

    public function index(Request $request)
    {

        $user = $request->user();
        $token = $user->createToken($user->name)->plainTextToken;
        return view('principal.principal', compact('token'));
    }
}
