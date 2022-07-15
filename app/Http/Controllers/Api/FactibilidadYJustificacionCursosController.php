<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\c_FactibilidadYJustificacionCursos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FactibilidadYJustificacionCursosController extends Controller
{
    public function all()
    {
        try {
            $factibilidades = c_FactibilidadYJustificacionCursos::all();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $factibilidades,
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

    public function get($idFactibilidad)
    {
        try {
            $factibilidad = c_FactibilidadYJustificacionCursos::find($idFactibilidad);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $factibilidad,
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
            'lugar' => 'required',
            'fecha' => 'required|date',
            'region' => 'required',
            'distrito' => 'required',
            'c_Municipio' => 'required',
            'localidad' => 'required',
            'nombre_representante' => 'required',
            'domicilio' => 'required',
            'telefono' => 'required',
            'idCurso' => 'required',
            'total_hombres' => 'required',
            'total_mujeres' => 'required',
            'total' => 'required',
            'infraestructura_adecuada' => 'required',
            'detalles' => 'required',
            'explicacion' => 'required',
            'positivo' => 'required',
            'razones' => 'required',
            'nombre_administrativo' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $factibilidad = new c_FactibilidadYJustificacionCursos();
            $factibilidad->lugar = $request->lugar;
            $factibilidad->fecha = $request->fecha;
            $factibilidad->region = $request->region;
            $factibilidad->distrito = $request->distrito;
            $factibilidad->c_Municipio = $request->c_Municipio;
            $factibilidad->localidad = $request->localidad;
            $factibilidad->nombre_representante = $request->nombre_representante;
            $factibilidad->domicilio = $request->domicilio;
            $factibilidad->telefono = $request->telefono;
            $factibilidad->idCurso = $request->idCurso;
            $factibilidad->total_hombres = $request->total_hombres;
            $factibilidad->total_mujeres = $request->total_mujeres;
            $factibilidad->total = $request->total;
            $factibilidad->infraestructura_adecuada = $request->infraestructura_adecuada;
            $factibilidad->detalles = $request->detalles;
            $factibilidad->explicacion = $request->explicacion;
            $factibilidad->positivo = $request->positivo;
            $factibilidad->razones = $request->razones;
            $factibilidad->nombre_administrativo = $request->nombre_administrativo;
            $factibilidad->save();
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
                "servEstatus" =>  "ERROR",
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
            $factibilidad = c_FactibilidadYJustificacionCursos::find($request->id);
            if ($request->lugar) $factibilidad->lugar = $request->lugar;
            if ($request->fecha) $factibilidad->fecha = $request->fecha;
            if ($request->region) $factibilidad->region = $request->region;
            if ($request->distrito) $factibilidad->distrito = $request->distrito;
            if ($request->c_Municipio) $factibilidad->c_Municipio = $request->c_Municipio;
            if ($request->localidad) $factibilidad->localidad = $request->localidad;
            if ($request->nombre_representante) $factibilidad->nombre_representante = $request->nombre_representante;
            if ($request->domicilio) $factibilidad->domicilio = $request->domicilio;
            if ($request->telefono) $factibilidad->telefono = $request->telefono;
            if ($request->idCurso) $factibilidad->idCurso = $request->idCurso;
            if ($request->total_hombres) $factibilidad->total_hombres = $request->total_hombres;
            if ($request->total_mujeres) $factibilidad->total_mujeres = $request->total_mujeres;
            if ($request->total) $factibilidad->total = $request->total;
            if ($request->infraestructura_adecuada) $factibilidad->infraestructura_adecuada = $request->infraestructura_adecuada;
            if ($request->detalles) $factibilidad->detalles = $request->detalles;
            if ($request->explicacion) $factibilidad->explicacion = $request->explicacion;
            if ($request->positivo) $factibilidad->positivo = $request->positivo;
            if ($request->razones) $factibilidad->razones = $request->razones;
            if ($request->nombre_administrativo) $factibilidad->nombre_administrativo = $request->nombre_administrativo;
            $factibilidad->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Datos actualizados correctamente!",
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
            $factibilidad = c_FactibilidadYJustificacionCursos::find($id);
            $factibilidad->delete();
            DB::commit();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Registro eliminado!",
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
