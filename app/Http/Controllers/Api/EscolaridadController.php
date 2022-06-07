<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_escolaridad;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EscolaridadController extends Controller
{
    public function all()
    {
        try {
            $escolaridad = C_escolaridad::all();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $escolaridad,
                "timeZone" => new Carbon(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "servEstatus" =>  "ERROR",
                "serverCode" => "500",
                "mensaje" =>  $th->getMessage(),
                "timeZone" => new Carbon(),
            ], 500);
        }
    }
}
