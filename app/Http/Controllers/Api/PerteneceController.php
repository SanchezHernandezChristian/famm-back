<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_pertenece;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PerteneceController extends Controller
{
    public function all()
    {
        try {
            $pertenece = C_pertenece::all();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $pertenece,
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
