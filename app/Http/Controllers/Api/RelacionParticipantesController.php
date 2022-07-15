<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\c_RelacionParticipantes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelacionParticipantesController extends Controller
{
    public function all()
    {
        try {
            $participantes = c_RelacionParticipantes::all();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $participantes,
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

    public function get($idParticipante)
    {
        try {
            $participante = c_RelacionParticipantes::find($idParticipante);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $participante,
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
            'nombres' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'sexo' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $participante = new c_RelacionParticipantes();
            $participante->nombres = $request->nombres;
            $participante->apellido_paterno = $request->apellido_paterno;
            $participante->apellido_materno = $request->apellido_materno;
            $participante->sexo = $request->sexo;
            if ($request->telefono) $participante->telefono = $request->telefono;
            if ($request->celular) $participante->celular = $request->celular;
            $participante->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Registro de perticipante exitoso!",
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
            $participante = c_RelacionParticipantes::find($request->id);
            if ($request->nombres) $participante->nombres = $request->nombres;
            if ($request->apellido_paterno) $participante->apellido_paterno = $request->apellido_paterno;
            if ($request->apellido_materno) $participante->apellido_materno = $request->apellido_materno;
            if ($request->sexo) $participante->sexo = $request->sexo;
            if ($request->telefono) $participante->telefono = $request->telefono;
            if ($request->celular) $participante->celular = $request->celular;
            $participante->save();
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
            $participante = c_RelacionParticipantes::find($id);
            $participante->delete();
            DB::commit();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Participante eliminado!",
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
