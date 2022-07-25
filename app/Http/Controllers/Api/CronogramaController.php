<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_contenidoCronograma;
use App\Models\C_cronograma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CronogramaController extends Controller
{
    public function all()
    {
        try {
            $cronogramas = C_cronograma::where("idDocente", auth()->user()->id)->get();
            $arr = array();

            foreach ($cronogramas as $cronograma) {
                $contenido = C_contenidoCronograma::where("idCronograma", $cronograma->idCronograma)->get();
                array_push($arr, array('cronograma' => $cronograma, 'contenido_cronograma' => $contenido));
            }

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $arr,
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
            $cronogramas = C_cronograma::where(["idDocente" => auth()->user()->id, "idCronograma" => $id])->get();
            $arr = array();

            foreach ($cronogramas as $cronograma) {
                $contenido = C_contenidoCronograma::where("idCronograma", $cronograma->idCronograma)->get();
                array_push($arr, array('cronograma' => $cronograma, 'contenido_cronograma' => $contenido));
            }

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $arr,
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
            'idCurso' => 'required',
            'tipo_curso' => 'required',
            'encargado_curso' => 'required',
            'contenido_cronograma' => 'required',
        ]);


        try {
            DB::beginTransaction();
            $cronograma = new C_cronograma();
            $cronograma->idDocente = auth()->user()->id;
            $cronograma->idCurso = $request->idCurso;
            $cronograma->tipo_curso = $request->tipo_curso;
            $cronograma->encargado_curso = $request->encargado_curso;
            $cronograma->save();
            DB::commit();

            DB::beginTransaction();
            foreach ($request->contenido_cronograma as $contenido) {
                $temario = new C_contenidoCronograma();
                $temario->idCronograma = $cronograma->idCronograma;
                $temario->unidad = $contenido["unidad"];
                $temario->tema = $contenido["tema"];
                $temario->subtema = $contenido["subtema"];
                $temario->horas = $contenido["horas"];
                $temario->periodo = $contenido["periodo"];
                $temario->save();
            }
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
            $cronograma = C_cronograma::find($request->idCronograma);
            if ($request->idCurso) $cronograma->idCurso = $request->idCurso;
            if ($request->tipo_curso) $cronograma->tipo_curso = $request->tipo_curso;
            if ($request->encargado_curso) $cronograma->encargado_curso = $request->encargado_curso;
            $cronograma->save();
            DB::commit();

            // DB::beginTransaction();
            // foreach ($request->contenido_cronograma as $contenido) {
            //     $temario = new C_contenidoCronograma();
            //     $temario->idCronograma = $cronograma->idCronograma;
            //     $temario->unidad = $contenido["unidad"];
            //     $temario->tema = $contenido["tema"];
            //     $temario->subtema = $contenido["subtema"];
            //     $temario->horas = $contenido["horas"];
            //     $temario->periodo = $contenido["periodo"];
            //     $temario->save();
            // }
            // DB::commit();

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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $contenido = C_contenidoCronograma::where("idCronograma", $id);
            $contenido->delete();

            $cronograma = C_cronograma::find($id);
            $cronograma->delete();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Cronograma de actividades eliminado!",
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
