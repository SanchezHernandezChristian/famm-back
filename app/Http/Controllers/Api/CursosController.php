<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\C_cursos;

class CursosController extends Controller
{
    public function all()
    {
        try {
            return C_cursos::all();
        } catch (\Throwable $th) {
            return response()->json([
                "servEstatus" =>  "ERROR",
                "serverCode" => "500",
                "mensaje" =>  "Error inespera",
                "timeZone" => new Carbon(),
            ], 500);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'horas_teoricas' => 'required|numeric',
            'horas_practicas' => 'required|numeric',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            DB::beginTransaction();
            $curso = new C_cursos();
            $curso->nombre = $request->nombre;
            $curso->descripcion = $request->descripcion;
            $curso->horas_teoricas = $request->horas_teoricas;
            $curso->horas_practicas = $request->horas_practicas;
            $curso->fecha_inicio = $request->fecha_inicio;
            $curso->fecha_fin = $request->fecha_fin;
            $curso->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Registro de curso exitoso!",
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
            $curso = C_cursos::find($request->id);
            $curso->nombre = $request->nombre;
            $curso->descripcion = $request->descripcion;
            $curso->horas_teoricas = $request->horas_teoricas;
            $curso->horas_practicas = $request->horas_practicas;
            $curso->fecha_inicio = $request->fecha_inicio;
            $curso->fecha_fin = $request->fecha_fin;
            $curso->save();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Curso actualizado!",
                "timeZone" => new Carbon(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "servEstatus" =>  "ERROR",
                "serverCode" => "500",
                "mensaje" =>  "Error inespera",
                "timeZone" => new Carbon(),
            ], 500);
        }
    }
}
