<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InformacionAdicionalUsuario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function all()
    {
        try {
            $users = DB::connection('mysql')
                ->table('users')
                ->leftJoin('c_roles', 'users.idRol', '=', 'c_roles.idRol')
                ->whereNull('users.deleted_at')
                ->where("users.id", '!=', auth()->user()->id)
                ->get(["users.id", "nombres", "primer_apellido", "segundo_apellido", "email", "user_name", "idCentro_capacitacion", "users.idRol", "nombre_rol", "created_at"]);
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $users,
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

    public function getUser($id)
    {
        try {
            $users = DB::connection('mysql')
                ->table('users')
                ->leftJoin('c_roles', 'users.idRol', '=', 'c_roles.idRol')
                ->whereNull('users.deleted_at')
                ->where("users.id", $id)
                ->get(["users.id", "nombres", "primer_apellido", "segundo_apellido", "email", "user_name", "idCentro_capacitacion", "users.idRol", "nombre_rol"]);
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "data" =>  $users,
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

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'primer_apellido' => 'required',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'max:15',
                'regex:/[a-záéíóúñ]/',
                'regex:/[A-ZÁÉÍÓÚÑ]/',
                'regex:/[0-9]/',
                'confirmed'
            ],
            'password_confirmation' => [
                'required',
                'min:8',
                'max:15',
                'regex:/[a-záéíóúñ]/',
                'regex:/[A-ZÁÉÍÓÚÑ]/',
                'regex:/[0-9]/'
            ],
        ]);

        try {
            DB::beginTransaction();
            $user = new User();
            $user->nombres = $request->name;
            $user->primer_apellido = $request->primer_apellido;
            if ($request->segundo_apellido) $user->segundo_apellido = $request->segundo_apellido;
            if ($request->user_name) $user->user_name = $request->user_name;
            if ($request->idCentro_capacitacion) $user->idCentro_capacitacion = $request->idCentro_capacitacion;
            $user->email = $request->email;
            $user->informacion_complementaria = '0';
            $user->password = Hash::make($request->password);
            if ($request->idRol) $user->idRol = $request->idRol;
            $user->save();
            DB::commit();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Registro de usuario exitoso!",
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
        if ($request->email) {
            $request->validate([
                'email' => 'email|unique:users',
            ]);
        }

        if ($request->password) {
            $request->validate([
                'password' => [
                    'min:8',
                    'max:15',
                    'regex:/[a-záéíóúñ]/',
                    'regex:/[A-ZÁÉÍÓÚÑ]/',
                    'regex:/[0-9]/',
                    'confirmed'
                ],
                'password_confirmation' => [
                    'min:8',
                    'max:15',
                    'regex:/[a-záéíóúñ]/',
                    'regex:/[A-ZÁÉÍÓÚÑ]/',
                    'regex:/[0-9]/'
                ],
            ]);
        }

        try {
            DB::beginTransaction();
            $user = User::find($request->id);
            if ($request->name) $user->nombres = $request->name;
            if ($request->primer_apellido) $user->primer_apellido = $request->primer_apellido;
            if ($request->segundo_apellido) $user->segundo_apellido = $request->segundo_apellido;
            if ($request->user_name) $user->user_name = $request->user_name;
            if ($request->email) $user->email = $request->email;
            if ($request->password) $user->password = Hash::make($request->password);
            if ($request->idRol) $user->idRol = $request->idRol;
            if ($request->idCentro_capacitacion) $user->idCentro_capacitacion = $request->idCentro_capacitacion;
            $user->save();
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
            InformacionAdicionalUsuario::where('IdUser', $id)->delete();
            $user = User::find($id);
            $user->delete();
            DB::commit();
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Usuario eliminado!",
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

    public function userProfile()
    {
        $rol = DB::connection('mysql')->table('c_roles')->where('idRol', auth()->user()->idRol)->first();

        return response()->json([
            "status" => 0,
            "msg" => "Acerca del perfil de usuario",
            "data" => ["nombres" => auth()->user()->nombres, "primer_apellido" => auth()->user()->primer_apellido, "segundo_apellido" => auth()->user()->segundo_apellido, "user_name" => auth()->user()->user_name, "idCentro_capacitacion" => auth()->user()->idCentro_capacitacion, "Email" => auth()->user()->email, "Rol" => $rol->nombre_rol]
        ]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);

            $user = User::where("email", "=", $request->email)->first();

            if (!isset($user->id)) {
                return response()->json([
                    "servEstatus" => "NOT_FOUND",
                    "serverCode" => "404",
                    "mensaje" => "Usuario no registrado",
                    "timeZone" => new Carbon(),
                ], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    "servEstatus" => "FORBIDDEN",
                    "serverCode" => "403",
                    "mensaje" => "Credenciales inválidas",
                    "timeZone" => new Carbon(),
                ], 404);
            }

            //creamos el token
            $token = $user->createToken("auth_token")->plainTextToken;
            $rol = DB::connection('mysql')->table('c_roles')->where('idRol', $user->idRol)->first();
            //si está todo ok
            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" => "¡Usuario logueado exitosamente!",
                "timeZone" => new Carbon(),
                "user" => ["Nombre" => $user->nombres, "Email" => $user->email, "EstatusPerfil" => $user->informacion_complementaria, "Rol" => $rol->nombre_rol],
                "token" => $token
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "servEstatus" =>  "ERROR",
                "serverCode" => "500",
                "mensaje" =>  $th->getMessage(),
                "timeZone" => new Carbon(),
            ], 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();

            return response()->json([
                "servEstatus" =>  "OK",
                "serverCode" => "200",
                "mensaje" =>  "Sesión cerrada correctamente",
                "timeZone" => new Carbon(),
            ]);
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
