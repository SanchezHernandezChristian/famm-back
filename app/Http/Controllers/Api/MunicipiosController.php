<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_municipios;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MunicipiosController extends Controller
{
    public function all()
    {
        try {
            $municipios = C_municipios::all();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "municipios" =>  $municipios,
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
