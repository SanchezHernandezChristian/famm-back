<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\InformacionAdicionalUsuario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InformacionAdicionalUsuarioController extends Controller
{
    public function all()
    {
        try {
            $informacion_adicional = InformacionAdicionalUsuario::where('IdUser', Auth::user()->id)->first();
            $path = Storage::disk('s3')->url($informacion_adicional->fotografia);
            return  response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "user" =>  $informacion_adicional,
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

    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'domicilio' => 'required',
            'fotografia' => 'required|mimes:jpg,png,jpeg|max:1024',
            'c_Municipio' => 'required',
            'email' => 'required|email',
            'curp' => 'required',
            'sexo' => 'required',
            'fecha_nacimiento' => 'required|date',
            'nacionalidad' => 'required',
            'edad' => 'required|numeric',
            'estado_civil' => 'required',
            'telefono' => 'required|numeric',
            'grupo_vulnerable' => 'required|numeric',
            'discapacidad' => 'required|numeric',
            'pertenece_a' => 'required',
            'escolaridad' => 'required',
            'lengua_indigena' => 'required|numeric',
            'motivo' => 'required',
            'situacion_laboral' => 'required|numeric',
            'firma_capacitando' => 'required'
        ]);

        try {
            if (Auth::user()->informacion_complementaria == 0) {
                $folder = "famm";

                DB::beginTransaction();
                $informacion_adicional = new InformacionAdicionalUsuario();
                $informacion_adicional->IdUser = Auth::user()->id;
                $informacion_adicional->nombre = $request->nombre;
                $informacion_adicional->apellido_paterno = $request->apellido_paterno;
                $informacion_adicional->apellido_materno = $request->apellido_materno;
                $informacion_adicional->domicilio = $request->domicilio;
                $image_path = Storage::disk('s3')->put($folder, $request->fotografia, 'public');
                $informacion_adicional->fotografia = $image_path;
                $informacion_adicional->c_Municipio = $request->c_Municipio;
                $informacion_adicional->email = $request->email;
                $informacion_adicional->curp = $request->curp;
                $informacion_adicional->sexo = $request->sexo;
                $informacion_adicional->fecha_nacimiento = $request->fecha_nacimiento;
                $informacion_adicional->nacionalidad = $request->nacionalidad;
                $informacion_adicional->edad = $request->edad;
                $informacion_adicional->estado_civil = $request->estado_civil;
                $informacion_adicional->telefono = $request->telefono;
                $informacion_adicional->grupo_vulnerable = $request->grupo_vulnerable;
                $informacion_adicional->discapacidad = $request->discapacidad;
                $informacion_adicional->pertenece_a = $request->pertenece_a;
                $informacion_adicional->escolaridad = $request->escolaridad;
                $informacion_adicional->lengua_indigena = $request->lengua_indigena;
                $informacion_adicional->motivo = $request->motivo;
                $informacion_adicional->situacion_laboral = $request->situacion_laboral;
                $informacion_adicional->firma_capacitando = $request->firma_capacitando;
                $informacion_adicional->save();
                DB::commit();

                $user = User::find(Auth::user()->id);
                $user->informacion_complementaria = 1;
                $user->save();

                return response()->json([
                    "servEstatus" =>  "OK",
                    "serverCode" => "200",
                    "mensaje" => "¡Información adicional registrada con exito!",
                    "timeZone" => new Carbon(),
                ], 200);
            } else {
                return response()->json([
                    "servEstatus" =>  "ERROR",
                    "serverCode" => "403",
                    "mensaje" =>  "¡Acceso denegado!",
                    "timeZone" => new Carbon(),
                ], 403);
            }
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
            if (Auth::user()->informacion_complementaria == 1) {
                $informacion_adicional = InformacionAdicionalUsuario::where('IdUser', Auth::user()->id)->first();
                if ($request->nombre) $informacion_adicional->nombre = $request->nombre;
                if ($request->apellido_paterno) $informacion_adicional->apellido_paterno = $request->apellido_paterno;
                if ($request->apellido_materno) $informacion_adicional->apellido_materno = $request->apellido_materno;
                if ($request->domicilio) $informacion_adicional->domicilio = $request->domicilio;

                if ($request->fotografia) {
                    Storage::disk('s3')->delete($informacion_adicional->fotografia);
                    $image_path = Storage::disk('s3')->put("famm", $request->fotografia, 'public');
                    $informacion_adicional->fotografia = $image_path;
                }

                if ($request->c_Municipio) $informacion_adicional->c_Municipio = $request->c_Municipio;
                if ($request->email) $informacion_adicional->email = $request->email;
                if ($request->curp) $informacion_adicional->curp = $request->curp;
                if ($request->sexo) $informacion_adicional->sexo = $request->sexo;
                if ($request->fecha_nacimiento) $informacion_adicional->fecha_nacimiento = $request->fecha_nacimiento;
                if ($request->nacionalidad) $informacion_adicional->nacionalidad = $request->nacionalidad;
                if ($request->edad) $informacion_adicional->edad = $request->edad;
                if ($request->estado_civil) $informacion_adicional->estado_civil = $request->estado_civil;
                if ($request->telefono) $informacion_adicional->telefono = $request->telefono;
                if ($request->grupo_vulnerable) $informacion_adicional->grupo_vulnerable = $request->grupo_vulnerable;
                if ($request->discapacidad) $informacion_adicional->discapacidad = $request->discapacidad;
                if ($request->pertenece_a) $informacion_adicional->pertenece_a = $request->pertenece_a;
                if ($request->escolaridad) $informacion_adicional->escolaridad = $request->escolaridad;
                if ($request->lengua_indigena) $informacion_adicional->lengua_indigena = $request->lengua_indigena;
                if ($request->motivo) $informacion_adicional->motivo = $request->motivo;
                if ($request->situacion_laboral) $informacion_adicional->situacion_laboral = $request->situacion_laboral;
                if ($request->firma_capacitando) $informacion_adicional->firma_capacitando = $request->firma_capacitando;
                $informacion_adicional->save();

                return response()->json([
                    "servEstatus" =>  "OK",
                    "serverCode" => "200",
                    "mensaje" => "¡Información actualizada!",
                    "timeZone" => new Carbon(),
                ], 200);
            } else {
                return response()->json([
                    "servEstatus" =>  "ERROR",
                    "serverCode" => "403",
                    "mensaje" =>  "¡Acceso denegado!",
                    "timeZone" => new Carbon(),
                ], 403);
            }
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
