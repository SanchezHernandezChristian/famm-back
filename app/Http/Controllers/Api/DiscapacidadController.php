<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_discapacidad;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiscapacidadController extends Controller
{
    public function all()
    {
        try {
            $discapacidad = C_discapacidad::all();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $discapacidad,
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
