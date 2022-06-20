<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_cursos;
use App\Models\C_especialidades;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspecialidadController extends Controller
{
    public function all()
    {
        try {

            $especialidades = DB::connection('mysql')
                ->table('c_especialidad')
                ->whereNull('deleted_at')
                ->get(["idEspecialidad", "nombre_especialidad", "clave_especialidad", "campo_formacion", "subsector", "sector", "created_at"]);

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

    public function get($id)
    {
        try {
            $especialidad = C_especialidades::find($id);
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $especialidad,
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

    public function create(Request $request)
    {
        $request->validate([
            'nombre_especialidad' => 'required',
            'clave_especialidad' => 'required',
            'campo_formacion' => 'required',
            'subsector' => 'required',
            'sector' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $especialidad = new C_especialidades();
            $especialidad->nombre_especialidad = $request->nombre_especialidad;
            $especialidad->clave_especialidad = $request->clave_especialidad;
            $especialidad->campo_formacion = $request->campo_formacion;
            $especialidad->subsector = $request->subsector;
            $especialidad->sector = $request->sector;
            $especialidad->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Registro de especialidad exitoso!",
                "timeZone" => new Carbon(),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                "servEstatus" =>  "NOT_FOUND",
                "serverCode" => "500",
                "mensaje" =>  "Error en consulta de datos de usuario",
                "timeZone" => new Carbon(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $especialidad = C_especialidades::find($request->id);
            if ($request->nombre_especialidad) $especialidad->nombre_especialidad = $request->nombre_especialidad;
            if ($request->clave_especialidad) $especialidad->clave_especialidad = $request->clave_especialidad;
            if ($request->campo_formacion) $especialidad->campo_formacion = $request->campo_formacion;
            if ($request->subsector) $especialidad->subsector = $request->subsector;
            if ($request->sector) $especialidad->sector = $request->sector;
            $especialidad->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Especialidad actualizada!",
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

    public function destroy($id)
    {
        $msg = "";
        $code = 0;

        try {
            DB::beginTransaction();
            $cursos = C_cursos::where('idEspecialidad', $id)->whereNull('deleted_at')->get();

            if (isset($cursos[0])) {
                $code = 403;
                $msg = "Error: La especialidad ya ha sido asignada a un curso.";
            } else {
                $code = 200;
                $msg = "¡Especialidad eliminada!";
                $especialidad = C_especialidades::find($id);
                $especialidad->delete();
                DB::commit();
            }

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => $code,
                "mensaje" => $msg,
                "timeZone" => new Carbon(),
            ], $code);
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
