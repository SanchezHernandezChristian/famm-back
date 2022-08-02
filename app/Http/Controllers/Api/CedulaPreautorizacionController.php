<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_cedulaPreautorizacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CedulaPreautorizacionController extends Controller
{
    public function all()
    {
        try {
            $cedula_preautorizacion = C_cedulaPreautorizacion::all();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $cedula_preautorizacion,
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

    public function allValid()
    {
        try {
            $cedula_preautorizacion = C_cedulaPreautorizacion::where('esValido', 1)->get();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $cedula_preautorizacion,
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
            $cedula_preautorizacion = C_cedulaPreautorizacion::find($id);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $cedula_preautorizacion,
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
            'idCurso' => 'required|numeric',
            'c_Municipio' => 'required',
            'idDocente' => 'required|numeric',
            'idEspecialidad' => 'required|numeric',
            'solicitaCurso' => 'required',
            'nombreSolicitaCurso' => 'required',
            'nombreRepresentante' => 'required',
            'region' => 'required',
            'distrito' => 'required',
            'localidad' => 'required',
            'sedeCurso' => 'required',
            'modalidadCurso' => 'required',
            'totalHorasCurso' => 'required|numeric',
            'costoHora' => 'required|numeric',
            'costoTotal' => 'required|numeric',
            'periodoInicio' => 'required|date',
            'periodoTermino' => 'required|date',
            'totalDiasCapacitacion' => 'required|numeric',
            'accionMovil' => 'required',
            'totalHorasSemana' => 'required|numeric',
            'grupoEtnico' => 'required',
            'totalInscritos' => 'required|numeric',
            'totalHombres' => 'required|numeric',
            'totalMujeres' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $cedula_preautorizacion = new C_cedulaPreautorizacion();
            $cedula_preautorizacion->idCurso = $request->idCurso;
            $cedula_preautorizacion->c_Municipio = $request->c_Municipio;
            $cedula_preautorizacion->idDocente = $request->idDocente;
            $cedula_preautorizacion->idEspecialidad = $request->idEspecialidad;
            $cedula_preautorizacion->solicitaCurso = $request->solicitaCurso;
            $cedula_preautorizacion->nombreSolicitaCurso = $request->nombreSolicitaCurso;
            $cedula_preautorizacion->nombreRepresentante = $request->nombreRepresentante;
            $cedula_preautorizacion->region = $request->region;
            $cedula_preautorizacion->distrito = $request->distrito;
            $cedula_preautorizacion->localidad = $request->localidad;
            $cedula_preautorizacion->sedeCurso = $request->sedeCurso;
            $cedula_preautorizacion->modalidadCurso = $request->modalidadCurso;
            $cedula_preautorizacion->totalHorasCurso = $request->totalHorasCurso;
            $cedula_preautorizacion->costoHora = $request->costoHora;
            $cedula_preautorizacion->costoTotal = $request->costoTotal;
            $cedula_preautorizacion->periodoInicio = $request->periodoInicio;
            $cedula_preautorizacion->periodoTermino = $request->periodoTermino;
            $cedula_preautorizacion->totalDiasCapacitacion = $request->totalDiasCapacitacion;
            $cedula_preautorizacion->accionMovil = $request->accionMovil;
            $cedula_preautorizacion->totalHorasSemana = $request->totalHorasSemana;
            $cedula_preautorizacion->grupoEtnico = $request->grupoEtnico;
            $cedula_preautorizacion->totalInscritos = $request->totalInscritos;
            $cedula_preautorizacion->totalHombres = $request->totalHombres;
            $cedula_preautorizacion->totalMujeres = $request->totalMujeres;
            $cedula_preautorizacion->esValido = 0;
            $cedula_preautorizacion->save();
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
            $cedula_preautorizacion = C_cedulaPreautorizacion::find($request->id);

            if ($request->idCurso) $cedula_preautorizacion->idCurso = $request->idCurso;
            if ($request->c_Municipio) $cedula_preautorizacion->c_Municipio = $request->c_Municipio;
            if ($request->idDocente) $cedula_preautorizacion->idDocente = $request->idDocente;
            if ($request->idEspecialidad) $cedula_preautorizacion->idEspecialidad = $request->idEspecialidad;
            if ($request->solicitaCurso) $cedula_preautorizacion->solicitaCurso = $request->solicitaCurso;
            if ($request->nombreSolicitaCurso) $cedula_preautorizacion->nombreSolicitaCurso = $request->nombreSolicitaCurso;
            if ($request->nombreRepresentante) $cedula_preautorizacion->nombreRepresentante = $request->nombreRepresentante;
            if ($request->region) $cedula_preautorizacion->region = $request->region;
            if ($request->distrito) $cedula_preautorizacion->distrito = $request->distrito;
            if ($request->localidad) $cedula_preautorizacion->localidad = $request->localidad;
            if ($request->sedeCurso) $cedula_preautorizacion->sedeCurso = $request->sedeCurso;
            if ($request->modalidadCurso) $cedula_preautorizacion->modalidadCurso = $request->modalidadCurso;
            if ($request->totalHorasCurso) $cedula_preautorizacion->totalHorasCurso = $request->totalHorasCurso;
            if ($request->costoHora) $cedula_preautorizacion->costoHora = $request->costoHora;
            if ($request->costoTotal) $cedula_preautorizacion->costoTotal = $request->costoTotal;
            if ($request->periodoInicio) $cedula_preautorizacion->periodoInicio = $request->periodoInicio;
            if ($request->periodoTermino) $cedula_preautorizacion->periodoTermino = $request->periodoTermino;
            if ($request->totalDiasCapacitacion) $cedula_preautorizacion->totalDiasCapacitacion = $request->totalDiasCapacitacion;
            if ($request->accionMovil) $cedula_preautorizacion->accionMovil = $request->accionMovil;
            if ($request->totalHorasSemana) $cedula_preautorizacion->totalHorasSemana = $request->totalHorasSemana;
            if ($request->grupoEtnico) $cedula_preautorizacion->grupoEtnico = $request->grupoEtnico;
            if ($request->totalInscritos) $cedula_preautorizacion->totalInscritos = $request->totalInscritos;
            if ($request->totalHombres) $cedula_preautorizacion->totalHombres = $request->totalHombres;
            if ($request->totalMujeres) $cedula_preautorizacion->totalMujeres = $request->totalMujeres;
            if ($request->esValido) $cedula_preautorizacion->esValido = $request->esValido;
            $cedula_preautorizacion->save();
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
            $cedula_preautorizacion = C_cedulaPreautorizacion::find($id);
            $cedula_preautorizacion->delete();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Cedula de preautorizacion eliminada!",
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
