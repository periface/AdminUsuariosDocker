<?php

namespace App\Http\Controllers;

use App\Models\Secretaria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SecretariaController extends Controller
{
    public function get()
    {
        $user = (Auth::user());
        Log::info($user);
        $secretarias = [];
        $secretariaId = $user['secretariaId'];
        if ($user['role'] === 'ADMIN') {
            $secretarias = Secretaria::all();
        } else {
            $secretarias = Secretaria::where('id', $secretariaId)->get();
        }
        return response()->json($secretarias);
    }
}
