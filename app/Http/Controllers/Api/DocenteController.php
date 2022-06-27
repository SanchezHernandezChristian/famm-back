<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_docente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
    public function all()
    {
        try {
            $docentes = C_docente::whereNull('deleted_at')->get();
            // $path = Storage::disk('s3')->url($docente->fotografia);
            $path = "";
            return  response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $docentes,
                "path" => $path,
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
            $docente = C_docente::find($id);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $docente,
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
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',

            'c_Municipio' => 'required',
            'curp' => 'required',
            'sexo' => 'required',
            'fecha_nacimiento' => 'required|date',
            'nacionalidad' => 'required',
            'edad' => 'required|numeric',
            'estado_civil' => 'required',
            'telefono' => 'required|numeric',
            'idEscolaridad' => 'required|numeric',
            'lengua_indigena' => 'required|numeric',
            'motivo' => 'required',
            'situacion_laboral' => 'required|numeric',

        ]);

        if ($request->grupo_vulnerable) {
            $request->validate(['grupo_vulnerable' => 'required|numeric']);
        }

        if ($request->idDiscapacidad) {
            $request->validate(['idDiscapacidad' => 'required|numeric']);
        }

        if ($request->idPertenece) {
            $request->validate(['idPertenece' => 'required|numeric']);
        }

        //'fotografia' => 'required|mimes:jpg,png,jpeg|max:1024',
        //'firma_capacitando' => 'required'
        try {
            $folder = "famm";

            DB::beginTransaction();
            $docente = new C_docente();
            $docente->nombre = $request->nombre;
            $docente->apellido_paterno = $request->apellido_paterno;
            $docente->apellido_materno = $request->apellido_materno;
            $docente->calle = $request->calle;
            $docente->numero = $request->numero;
            $docente->colonia = $request->colonia;
            $docente->localidad = $request->localidad;
            $docente->cp = $request->cp;
            // $image_path = Storage::disk('s3')->put($folder, $request->fotografia, 'public');
            // $docente->fotografia = $image_path;
            $docente->c_Municipio = $request->c_Municipio;
            $docente->curp = $request->curp;
            $docente->sexo = $request->sexo;
            $docente->fecha_nacimiento = $request->fecha_nacimiento;
            $docente->nacionalidad = $request->nacionalidad;
            $docente->edad = $request->edad;
            $docente->estado_civil = $request->estado_civil;
            $docente->telefono = $request->telefono;
            if ($request->grupo_vulnerable) $docente->grupo_vulnerable = $request->grupo_vulnerable;
            if ($request->idDiscapacidad) $docente->idDiscapacidad = $request->idDiscapacidad;
            if ($request->idPertenece) $docente->idPertenece = $request->idPertenece;

            $docente->idEscolaridad = $request->idEscolaridad;
            $docente->lengua_indigena = $request->lengua_indigena;
            $docente->motivo = $request->motivo;
            $docente->situacion_laboral = $request->situacion_laboral;
            // $docente->firma_capacitando = $request->firma_capacitando;
            $docente->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Docente registrado con exito!",
                "data" => $docente->idDocente,
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
            $docente = C_docente::find($request->id);
            if ($request->nombre) $docente->nombre = $request->nombre;
            if ($request->apellido_paterno) $docente->apellido_paterno = $request->apellido_paterno;
            if ($request->apellido_materno) $docente->apellido_materno = $request->apellido_materno;
            if ($request->calle) $docente->calle = $request->calle;
            if ($request->numero) $docente->numero = $request->numero;
            if ($request->colonia) $docente->colonia = $request->colonia;
            if ($request->localidad) $docente->localidad = $request->localidad;
            if ($request->cp) $docente->cp = $request->cp;

            // if ($request->fotografia) {
            //     Storage::disk('s3')->delete($docente->fotografia);
            //     $image_path = Storage::disk('s3')->put("famm", $request->fotografia, 'public');
            //     $docente->fotografia = $image_path;
            // }

            if ($request->c_Municipio) $docente->c_Municipio = $request->c_Municipio;
            if ($request->curp) $docente->curp = $request->curp;
            if ($request->sexo) $docente->sexo = $request->sexo;
            if ($request->fecha_nacimiento) $docente->fecha_nacimiento = $request->fecha_nacimiento;
            if ($request->nacionalidad) $docente->nacionalidad = $request->nacionalidad;
            if ($request->edad) $docente->edad = $request->edad;
            if ($request->estado_civil) $docente->estado_civil = $request->estado_civil;
            if ($request->telefono) $docente->telefono = $request->telefono;
            if ($request->grupo_vulnerable) $docente->grupo_vulnerable = $request->grupo_vulnerable;
            if ($request->idDiscapacidad) $docente->idDiscapacidad = $request->idDiscapacidad;
            if ($request->idPertenece) $docente->idPertenece = $request->idPertenece;
            if ($request->idEscolaridad) $docente->idEscolaridad = $request->idEscolaridad;
            if ($request->lengua_indigena) $docente->lengua_indigena = $request->lengua_indigena;
            if ($request->motivo) $docente->motivo = $request->motivo;
            if ($request->situacion_laboral) $docente->situacion_laboral = $request->situacion_laboral;
            // if ($request->firma_capacitando) $docente->firma_capacitando = $request->firma_capacitando;
            $docente->save();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Información actualizada!",
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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $docente = C_docente::find($id);
            $docente->delete();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Docente eliminado!",
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
