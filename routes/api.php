<?php

use App\Http\Controllers\Api\CentrosdecapacitacionController;
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
use App\Http\Controllers\Api\RolesController;

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
    Route::get('logout', [UserController::class, 'logout']);

    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::put('update-profile', [UserController::class, 'update']);
    Route::delete('delete-profile/{id}', [UserController::class, 'destroy']);
    Route::get('users', [UserController::class, 'all']);
    Route::get('get-user/{id}', [UserController::class, 'getUser']);

    Route::post('create-grade', [CursosController::class, 'create']);
    Route::put('update-grade', [CursosController::class, 'update']);
    Route::get('get-grade/{clave_curso}', [CursosController::class, 'get']);
    Route::delete('delete-grade/{id}', [CursosController::class, 'destroy']);

    Route::post('additional-user-information', [InformacionAdicionalUsuarioController::class, 'create']);
    Route::put('update-additional-user-information', [InformacionAdicionalUsuarioController::class, 'update']);
    Route::get('all-additional-user-information', [InformacionAdicionalUsuarioController::class, 'all']);

    Route::get('all-training-center', [CentrosdecapacitacionController::class, 'all']);
    Route::post('add-training-center', [CentrosdecapacitacionController::class, 'create']);
    Route::delete('delete-training-center/{id}', [CentrosdecapacitacionController::class, 'destroy']);
    Route::put('update-training-center', [CentrosdecapacitacionController::class, 'update']);
    Route::get('get-training-center/{id}', [CentrosdecapacitacionController::class, 'get']);

    Route::get('all-roles', [RolesController::class, 'all']);

    Route::get('get-specialty/{id}', [EspecialidadController::class, 'get']);
    Route::post('create-specialty', [EspecialidadController::class, 'create']);
    Route::put('update-specialty', [EspecialidadController::class, 'update']);
    Route::delete('delete-specialty/{id}', [EspecialidadController::class, 'destroy']);
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
