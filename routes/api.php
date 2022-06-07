<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CursosController;
use App\Http\Controllers\Api\DiscapacidadController;
use App\Http\Controllers\Api\EscolaridadController;
use App\Http\Controllers\Api\EspecialidadController;
use App\Http\Controllers\Api\InformacionAdicionalUsuarioController;
use App\Http\Controllers\Api\MunicipiosController;
use App\Http\Controllers\Api\PerteneceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('logout', [UserController::class, 'logout']);
    Route::post('create-grade', [CursosController::class, 'create']);
    Route::put('update-grade', [CursosController::class, 'update']);
    Route::get('get-grade/{clave_curso}', [CursosController::class, 'get']);
    Route::delete('delete-grade/{id}', [CursosController::class, 'update']);
    Route::post('additional-user-information', [InformacionAdicionalUsuarioController::class, 'create']);
    Route::put('update-additional-user-information', [InformacionAdicionalUsuarioController::class, 'update']);
    Route::get('all-additional-user-information', [InformacionAdicionalUsuarioController::class, 'all']);
});
Route::get('all-township', [MunicipiosController::class, 'all']);
Route::get('all-grade', [CursosController::class, 'all']);
Route::get('all-specialty-information', [EspecialidadController::class, 'all']);

Route::get('discapacidades', [DiscapacidadController::class, 'all']);
Route::get('escolaridades', [EscolaridadController::class, 'all']);
Route::get('pertenece-a', [PerteneceController::class, 'all']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
