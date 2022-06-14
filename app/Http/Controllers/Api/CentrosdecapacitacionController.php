<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_centrosdecapacitacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CentrosdecapacitacionController extends Controller
{
    public function all()
    {
        try {
            $centro_capacitacion = C_centrosdecapacitacion::all();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $centro_capacitacion,
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

    public function get($id)
    {
        try {
            $centro_capacitacion = C_centrosdecapacitacion::find($id);
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $centro_capacitacion,
                "timeZone" => new Carbon(),
            ], 200);;
        } catch (\Throwable $th) {
            return response()->json([
                "servEstatus" =>  "ERROR",
                "serverCode" => "500",
                "mensaje" =>  $th->getMessage(),
                "timeZone" => new Carbon(),
            ], 500);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'director' => 'required',
            'telefono' => 'required|min:10|max:10',
            'direccion' => 'required',
            'tipo' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $centro_capacitacion = new C_centrosdecapacitacion();
            $centro_capacitacion->nombre = $request->nombre;
            $centro_capacitacion->director = $request->director;
            $centro_capacitacion->telefono = $request->telefono;
            $centro_capacitacion->direccion = $request->direccion;
            $centro_capacitacion->tipo = $request->tipo;
            $centro_capacitacion->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Registro exitoso!",
                "timeZone" => new Carbon(),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                "servEstatus" =>  "NOT_FOUND",
                "serverCode" => "500",
                "mensaje" =>  $th->getMessage(),
                "timeZone" => new Carbon(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $centro_capacitacion = C_centrosdecapacitacion::find($request->id);
            if ($request->nombre) $centro_capacitacion->nombre = $request->nombre;
            if ($request->director) $centro_capacitacion->director = $request->director;
            if ($request->telefono) $centro_capacitacion->telefono = $request->telefono;
            if ($request->direccion) $centro_capacitacion->direccion = $request->direccion;
            if ($request->tipo) $centro_capacitacion->tipo = $request->tipo;
            $centro_capacitacion->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Centro de capacitación actualizado!",
                "timeZone" => new Carbon(),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                "servEstatus" =>  "ERROR",
                "serverCode" => "500",
                "mensaje" =>  $th->getMessage(),
                "timeZone" => new Carbon(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $centro_capacitacion = C_centrosdecapacitacion::find($id);
            $centro_capacitacion->delete();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Centro de capacitación eliminado!",
                "timeZone" => new Carbon(),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "servEstatus" =>  "ERROR",
                "serverCode" => "500",
                "mensaje" =>  $th->getMessage(),
                "timeZone" => new Carbon(),
            ], 500);
        }
    }
}
