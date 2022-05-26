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
            $cursos = DB::connection('mysql')
                ->table('c_cursos')
                ->leftJoin('c_especialidad', 'c_cursos.idEspecialidad', '=', 'c_especialidad.idEspecialidad')
                ->get(['nombre_curso', 'duracion_horas', 'clave_curso', 'nombre_especialidad', 'clave_especialidad', 'campo_formacion', 'subsector', 'sector']);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "cursos" =>  $cursos,
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
            'duracion' => 'required|numeric',
            'clave' => 'required',
            'idEspecialidad' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $curso = new C_cursos();
            $curso->nombre_curso = $request->nombre;
            $curso->duración_horas = $request->duracion;
            $curso->clave_curso = $request->clave;
            $curso->idEspecialidad = $request->idEspecialidad;
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
            $curso->nombre_curso = $request->nombre;
            $curso->duracion_horas = $request->duracion;
            $curso->clave_curso = $request->clave;
            $curso->idEspecialidad = $request->idEspecialidad;
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
