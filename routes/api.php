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
use App\Http\Controllers\API\SuscripcionController;
use App\Http\Controllers\API\EstudianteController;

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

// Rutas públicas
Route::get('validate-token', [AuthController::class, 'validateToken']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'store']);
Route::apiResource('leccion', LeccionController::class);
Route::apiResource('ejercicioIA', EjercicioIAController::class);

// Rutas protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // Endpoints administrativos
    Route::apiResource('nivel', NivelController::class);
    Route::post('/ejercicio', [EjercicioController::class, 'store']);
    Route::apiResource('ejercicio', EjercicioController::class);
    Route::apiResource('progreso', ProgresoController::class);
    Route::apiResource('errores', ErroresController::class);
    Route::apiResource('suscripcion', SuscripcionController::class);

    // Endpoints para rol estudiante
    Route::get('/estudiantes/{id}', [EstudianteController::class, 'getById']);
    Route::get('/estudiantes/user/{id}', [EstudianteController::class, 'getByUserId']);
    Route::get('/nivel/{id}/lecciones', [NivelController::class, 'showLessons']);
    Route::get('/leccion/{id}/ejercicios', [LeccionController::class, 'getExercises']);
    Route::get('/user/progreso', [ProgresoController::class, 'getProgress']);
    Route::post('/leccion/{id}/completada', [ProgresoController::class, 'markComplete']);
    Route::post('/ejercicio/{id}/submit', [EjercicioController::class, 'submit']);
    Route::get('/user/suscripcion', [EstudianteController::class, 'getSubscription']);
    Route::put('/user/suscripcion', [EstudianteController::class, 'updateSubscription']);
    Route::get('/user/ejercicioIA', [EjercicioIAController::class, 'getAdaptiveExercises']);
    Route::post('/ejercicioIA/{id}/submit', [EjercicioIAController::class, 'submitAdaptivo']);
    Route::get('/nivel/{id}/completado', [ProgresoController::class, 'hasCompletedAllLessons']);
    Route::post('/nivel/{id}/completado', [ProgresoController::class, 'markLevelComplete']);
    Route::put('/user/nivel-actual', [EstudianteController::class, 'updateCurrentLevel']);
    Route::post('/user/avanzar-nivel', [ProgresoController::class, 'advanceToNextLevel']);
   

    // Obtener usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
