<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\C_cursos;
use App\Models\C_cursoUnidad;

class CursosController extends Controller
{
    public function all()
    {
        try {
            $cursos = DB::connection('mysql')
                ->table('c_cursos')
                ->leftJoin('c_especialidad', 'c_cursos.idEspecialidad', '=', 'c_especialidad.idEspecialidad')
                ->whereNull('c_cursos.deleted_at')
                ->get(['idCurso', 'nombre_curso', 'duracion_horas', 'clave_curso', 'descripcion_curso', 'c_cursos.idEspecialidad', 'nombre_especialidad', 'clave_especialidad', 'campo_formacion', 'subsector', 'sector', 'c_cursos.created_at']);

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

    public function get($clave)
    {
        try {
            $curso = DB::connection('mysql')
                ->table('c_cursos')
                ->where('clave_curso', 'LIKE', "%$clave%")
                ->whereNull('deleted_at')
                ->first(['idCurso', 'nombre_curso', 'duracion_horas', 'clave_curso', 'descripcion_curso', 'idEspecialidad']);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "curso" =>  $curso,
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
            'nombre_curso' => 'required',
            'duracion_horas' => 'required|numeric',
            'clave_curso' => 'required',
            'idEspecialidad' => 'required',
            'descripcion_curso' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $curso = new C_cursos();
            $curso->nombre_curso = $request->nombre_curso;
            $curso->duracion_horas = $request->duracion_horas;
            $curso->clave_curso = $request->clave_curso;
            $curso->descripcion_curso = $request->descripcion_curso;
            $curso->idEspecialidad = $request->idEspecialidad;
            $curso->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "??Registro de curso exitoso!",
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
            $curso = C_cursos::find($request->id);
            if ($request->nombre_curso) $curso->nombre_curso = $request->nombre_curso;
            if ($request->duracion_horas) $curso->duracion_horas = $request->duracion_horas;
            if ($request->clave_curso) $curso->clave_curso = $request->clave_curso;
            if ($request->descripcion_curso) $curso->descripcion_curso = $request->descripcion_curso;
            if ($request->idEspecialidad) $curso->idEspecialidad = $request->idEspecialidad;
            $curso->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "??Datos actualizados correctamente!",
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
            $curso = C_cursos::find($id);
            $curso->delete();
            DB::commit();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "??Curso eliminado!",
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

    public function assign(Request $request)
    {
        $request->validate([
            'idCurso' => 'required',
            'idUnidad' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $curso = new C_cursoUnidad();
            $curso->idCurso = $request->idCurso;
            $curso->idUnidad = $request->idUnidad;
            $curso->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "??Asignaci??n de curso exitoso!",
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
    public function allAssign(Request $request)
    {
        try {
            $cursosUnidad = DB::connection('mysql')
                ->table('c_cursos')
                ->rightJoin('curso_unidad', 'c_cursos.idCurso', '=', 'curso_unidad.idCurso')
                ->leftJoin('c_centrosdecapacitacion', 'c_centrosdecapacitacion.id', '=', 'curso_unidad.idUnidad')
                ->whereNull('c_cursos.deleted_at')
                ->get(['c_cursos.idCurso', 'nombre_curso', 'duracion_horas', 'clave_curso', 'descripcion_curso', 'idUnidad', 'c_centrosdecapacitacion.id',  'c_centrosdecapacitacion.nombre']);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "cursos" =>  $cursosUnidad,
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

    public function allAssignByCenter($id)
    {
        try {
            $cursosUnidad = DB::connection('mysql')
                ->table('c_cursos')
                ->rightJoin('curso_unidad', 'c_cursos.idCurso', '=', 'curso_unidad.idCurso')
                ->leftJoin('c_centrosdecapacitacion', 'c_centrosdecapacitacion.id', '=', 'curso_unidad.idUnidad')
                ->whereNull('c_cursos.deleted_at')
                ->where('c_centrosdecapacitacion.id', '=', $id)
                ->get(['c_cursos.idCurso', 'nombre_curso', 'duracion_horas', 'clave_curso', 'descripcion_curso', 'idUnidad', 'c_centrosdecapacitacion.id',  'c_centrosdecapacitacion.nombre']);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "cursos" =>  $cursosUnidad,
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
}
