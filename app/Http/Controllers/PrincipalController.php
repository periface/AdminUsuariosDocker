<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    //

    public function index(Request $request)
    {
        $user = $request->user();
        $area = Area::find($user->areaId);
        $token = $user->createToken($user->name)->plainTextToken;
        return view('principal.principal', compact('token', 'area'));
    }
}
