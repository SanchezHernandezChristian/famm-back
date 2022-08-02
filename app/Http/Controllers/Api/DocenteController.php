<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\C_docente;
use App\Models\C_docenteCurso;
use App\Models\C_docenteDocumento;
use App\Models\C_docenteExperiencia;
use App\Models\C_docenteExperienciaLaboral;
use App\Models\C_docenteFormacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocenteController extends Controller
{
    public function all()
    {
        try {
            $docentes = C_docente::whereNull('deleted_at')->get();
            return  response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $docentes,
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
            $documentos = C_docenteDocumento::where(["idDocente" => $id, "tipo_comprobante" => "documentos_presenta"]);
            $experiencia = C_docenteExperiencia::find($id);
            $formacion = C_docenteFormacion::find($id);
            $experiencia_laboral = C_docenteExperienciaLaboral::find($id);
            $cursos = C_docenteCurso::find($id);

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $docente,
                "documentos" => $documentos,
                "experiencia" => $experiencia,
                "formacion" => $formacion,
                "experiencia_laboral" => $experiencia_laboral,
                "cursos" => $cursos,
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

    public function pagina_1(Request $request)
    {
        $request->validate([
            'fecha_ingreso' => 'required|date',
            'estatus' => 'required',
            'clave' => 'required',
            'certificado' => 'required',


            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',

            'calle' => 'required',
            'numero' => 'required',
            'colonia' => 'required',
            'localidad' => 'required',
            'c_Municipio' => 'required',
            'cp' => 'required',

            'rfc' => 'required',
            'curp' => 'required',
            'numero_registro' => 'required',
            'sexo' => 'required',
            'fecha_nacimiento' => 'required|date',
            'nacionalidad' => 'required',
            'edad' => 'required|numeric',
            'estado_civil' => 'required',
            'telefono' => 'required|numeric',
            'idEscolaridad' => 'required|numeric',

        ]);

        // 'fotografia' => 'required|mimes:jpg,png,jpeg|max:1024',
        try {
            DB::beginTransaction();
            $docente = new C_docente();
            $docente->fecha_ingreso = $request->fecha_ingreso;
            $docente->estatus = $request->estatus;
            $docente->clave = $request->clave;
            $docente->certificado = $request->certificado;
            // $image_path = Storage::disk('s3')->put("famm", $request->fotografia, 'public');
            // $docente->fotografia = $image_path;

            $docente->nombre = $request->nombre;
            $docente->apellido_paterno = $request->apellido_paterno;
            $docente->apellido_materno = $request->apellido_materno;

            $docente->calle = $request->calle;
            $docente->numero = $request->numero;
            $docente->colonia = $request->colonia;
            $docente->localidad = $request->localidad;
            $docente->c_Municipio = $request->c_Municipio;
            $docente->cp = $request->cp;

            $docente->telefono = $request->telefono;
            $docente->estado_civil = $request->estado_civil;
            $docente->nacionalidad = $request->nacionalidad;
            $docente->fecha_nacimiento = $request->fecha_nacimiento;
            $docente->edad = $request->edad;
            $docente->sexo = $request->sexo;
            $docente->rfc = $request->rfc;
            $docente->curp = $request->curp;
            $docente->numero_registro = $request->numero_registro;
            $docente->idEscolaridad = $request->idEscolaridad;
            $docente->esValido = 0;

            $docente->save();
            DB::commit();

            DB::beginTransaction();
            //Aqui se inserta el documento 
            $docente_documento = new C_docenteDocumento();
            $docente_documento->idDocente = $docente->idDocente;
            $docente_documento->tipo_comprobante = "comprobante_estudios";
            $doc_path = Storage::disk('s3')->put("famm-docs", $request->documento_obtenido, 'public');
            $docente_documento->path = $doc_path;
            $docente_documento->save();


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

    public function pagina_2(Request $request)
    {
        try {
            DB::beginTransaction();
            $experiencia_docente = C_docenteExperiencia::where("idDocente", $request->idDocente);
            if (isset($experiencia_docente[0])) {
                C_docenteExperiencia::where("idDocente", $request->idDocente)->delete();
            }

            foreach ($request->experienciaDocente as $_experiencia) {
                $experiencia = new C_docenteExperiencia();
                $experiencia->idDocente = $request->idDocente;
                $experiencia->nombre_curso = $_experiencia["nombre_curso"];
                $experiencia->nombre_institucion = $_experiencia["nombre_institucion"];
                $experiencia->periodo = $_experiencia["periodo"];
                $experiencia->documento = $_experiencia["documento"];
                $experiencia->save();
            }
            DB::commit();

            DB::beginTransaction();
            $formacion_docente = C_docenteFormacion::where("idDocente", $request->idDocente);
            if (isset($formacion_docente[0])) {
                C_docenteFormacion::where("idDocente", $request->idDocente)->delete();
            }

            foreach ($request->formacionDocente as $_formacion) {
                $formacion = new C_docenteFormacion();
                $formacion->idDocente = $request->idDocente;
                $formacion->nombre_curso = $_formacion["nombre_curso"];
                $formacion->nombre_institucion = $_formacion["nombre_institucion"];
                $formacion->periodo = $_formacion["periodo"];
                $formacion->documento = $_formacion["documento"];
                $formacion->save();
            }
            DB::commit();

            DB::beginTransaction();
            $experiencia_laboral_docente = C_docenteExperienciaLaboral::where("idDocente", $request->idDocente);
            if (isset($experiencia_laboral_docente[0])) {
                C_docenteExperienciaLaboral::where("idDocente", $request->idDocente)->delete();
            }

            foreach ($request->experienciaLaboral as $_experienciaLaboral) {
                $experiencia_laboral = new C_docenteExperienciaLaboral();
                $experiencia_laboral->idDocente = $request->idDocente;
                $experiencia_laboral->nombre_institucion = $_experienciaLaboral["nombre_institucion"];
                $experiencia_laboral->puesto = $_experienciaLaboral["puesto"];
                $experiencia_laboral->periodo = $_experienciaLaboral["periodo"];
                $experiencia_laboral->save();
            }
            DB::commit();

            DB::beginTransaction();
            $cursos = C_docenteCurso::where("idDocente", $request->idDocente);
            if (isset($cursos[0])) {
                C_docenteCurso::where("idDocente", $request->idDocente)->delete();
            }

            foreach ($request->cursos as $curso) {
                $docente_curso = new C_docenteCurso();
                $docente_curso->idDocente = $request->idDocente;
                $docente_curso->idCurso = $curso["idCurso"];
                $docente_curso->save();
            }
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Datos registrados con exito!",
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

    public function pagina_3(Request $request)
    {
        try {
            DB::beginTransaction();
            $docente_documento = C_docenteDocumento::where(["idDocente" => $request->id, "tipo_comprobante" => "documentos_presenta"]);
            if (isset($docente_documento[0])) {
                C_docenteDocumento::where(["idDocente" => $request->id, "tipo_comprobante" => "documentos_presenta"])->delete();
            }

            foreach ($request->documentos as $documeto) {
                $docente_documento = new C_docenteDocumento();
                $docente_documento->idDocente = $request->id;
                $docente_documento->tipo_comprobante = "documentos_presenta";
                $doc_path = Storage::disk('s3')->put("famm-docs", $documeto, 'public');
                $docente_documento->path = $doc_path;
                $docente_documento->save();
            }
            DB::commit();


            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Documentos registrados con exito!",
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
            $docente = C_docente::find($request->id);
            if ($request->fecha_ingreso) $docente->fecha_ingreso = $request->fecha_ingreso;
            if ($request->estatus) $docente->estatus = $request->estatus;
            if ($request->clave) $docente->clave = $request->clave;
            if ($request->certificado) $docente->certificado = $request->clave;

            // if ($request->fotografia) {
            //     $image_path = Storage::disk('s3')->put("famm", $request->fotografia, 'public');
            //     $docente->fotografia = $image_path;
            // }

            if ($request->nombre) $docente->nombre = $request->nombre;
            if ($request->apellido_paterno) $docente->apellido_paterno = $request->apellido_paterno;
            if ($request->apellido_materno) $docente->apellido_materno = $request->apellido_materno;

            if ($request->calle) $docente->calle = $request->calle;
            if ($request->numero) $docente->numero = $request->numero;
            if ($request->colonia) $docente->colonia = $request->colonia;
            if ($request->localidad) $docente->localidad = $request->localidad;
            if ($request->c_Municipio) $docente->c_Municipio = $request->c_Municipio;
            if ($request->cp) $docente->cp = $request->cp;

            if ($request->telefono) $docente->telefono = $request->telefono;
            if ($request->estado_civil) $docente->estado_civil = $request->estado_civil;
            if ($request->nacionalidad) $docente->nacionalidad = $request->nacionalidad;
            if ($request->fecha_nacimiento) $docente->fecha_nacimiento = $request->fecha_nacimiento;
            if ($request->edad) $docente->edad = $request->edad;
            if ($request->sexo) $docente->sexo = $request->sexo;
            if ($request->rfc) $docente->rfc = $request->rfc;
            if ($request->curp) $docente->curp = $request->curp;
            if ($request->numero_registro) $docente->numero_registro = $request->numero_registro;
            if ($request->idEscolaridad) $docente->idEscolaridad = $request->idEscolaridad;
            if ($request->esValido) $docente->esValido = $request->esValido;
            $docente->save();

            DB::commit();

            // if ($request->idEscolaridad) {
            //     DB::beginTransaction();
            //     //Aqui se inserta el documento 
            //     $docente_documento = C_docenteDocumento::where(["idDocente" => $request->id, "tipo_comprobante" => "comprobante_estudios"]);
            //     $doc_path = Storage::disk('s3')->put("famm-docs", $request->documento_obtenido, 'public');
            //     $docente_documento->path = $doc_path;
            //     DB::commit();
            // }

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

            C_docente::find($id)->delete();
            C_docenteDocumento::find($id)->delete();
            C_docenteExperiencia::find($id)->delete();
            C_docenteFormacion::find($id)->delete();
            C_docenteExperienciaLaboral::find($id)->delete();
            C_docenteCurso::find($id)->delete();
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
