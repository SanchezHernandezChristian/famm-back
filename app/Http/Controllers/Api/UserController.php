<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'max:15',
                'confirmed'
            ],
            'password_confirmation' => [
                'required',
                'min:8',
                'max:15',
            ],
        ]);

        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->informacion_complementaria = '0';
            $user->password = Hash::make($request->password);
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

    public function userProfile()
    {
        $rol = DB::connection('mysql')->table('c_roles')->where('idRol', auth()->user()->idRol)->first();

        return response()->json([
            "status" => 0,
            "msg" => "Acerca del perfil de usuario",
            "data" => ["Nombre" => auth()->user()->name, "Email" => auth()->user()->email, "Rol" => $rol->nombre_rol]
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
                "user" => ["Nombre" => $user->name, "Email" => $user->email, "EstatusPerfil" => $user->informacion_complementaria, "Rol" => $rol->nombre_rol],
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
