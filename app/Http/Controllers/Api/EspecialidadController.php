<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\C_especialidades;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    public function all()
    {
        try {
            $especialidades = C_especialidades::all();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "especialidades" =>  $especialidades,
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
