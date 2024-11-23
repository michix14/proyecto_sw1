<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EjercicioController;
use App\Http\Controllers\API\EjercicioIAController;
use App\Http\Controllers\API\ErroresController;
use App\Http\Controllers\API\LeccionController;
use App\Http\Controllers\API\NivelController;
use App\Http\Controllers\API\ProgresoController;
use App\Http\Controllers\API\UserController;
use App\Models\EjercicioIA;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('validate-token',[AuthController::class, 'validateToken']);
Route::post('login',[AuthController::class, 'login']);
Route::post('register', [UserController::class, 'store']);
Route::apiResource('nivel', NivelController::class);
Route::apiResource('leccion', LeccionController::class);
Route::apiResource('ejercicio', EjercicioController::class);
Route::apiResource('ejercicioIA', EjercicioIAController::class);
Route::apiResource('progreso', ProgresoController::class);
Route::apiResource('errores', ErroresController::class);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('logout',[AuthController::class, 'logout']);
});
