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

Route::get('validate-token', [AuthController::class, 'validateToken']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'store']);
Route::apiResource('leccion', LeccionController::class);
Route::apiResource('ejercicioIA', EjercicioIAController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    //endpoints administrativos
    Route::apiResource('nivel', NivelController::class);
    Route::post('/ejercicio', [EjercicioController::class, 'store']);
    Route::apiResource('ejercicio', EjercicioController::class);
    Route::apiResource('progreso', ProgresoController::class);
    Route::apiResource('errores', ErroresController::class);
    Route::apiResource('suscripcion', SuscripcionController::class);

    //endpoints para rol estudiante:
    //obtener las lecciones de un nivel
    Route::get('/nivel/{id}/lecciones', [NivelController::class, 'showLessons']);
    //obtener los ejercicios de una leccion
    Route::get('/leccion/{id}/ejercicios', [LeccionController::class, 'getExercises']);
    //obtener el progreso del usuario
    Route::get('/user/progreso', [ProgresoController::class, 'getProgress']);
    //marcar la leccion como completada
    Route::post('/leccion/{id}/completada', [ProgresoController::class, 'markComplete']);
    //Enviar respuesta a los ejercicios y verifica
    Route::post('/ejercicio/{id}/submit', [EjercicioController::class, 'submit']);
    // Gestión de suscripciones para estudiantes
    Route::get('/user/suscripcion', [EstudianteController::class, 'getSubscription']);
    Route::put('/user/suscripcion', [EstudianteController::class, 'updateSubscription']);
    //Enviar respuesta a los ejerciciosIA y verifica 
    Route::get('/user/ejercicioIA', [EjercicioIAController::class, 'getAdaptiveExercises']);
    //verifica para los ejercicios IA en caso de usar
    Route::post('/ejercicioIA/{id}/submit', [EjercicioIAController::class, 'submitAdaptivo']);
    // Verificar si completó todas las lecciones de un nivel
    Route::get('/nivel/{id}/completado', [ProgresoController::class, 'hasCompletedAllLessons']);
    // Marcar todas las lecciones de un nivel como completadas
    Route::post('/nivel/{id}/completado', [ProgresoController::class, 'markLevelComplete']);
    // Actualizar el nivel actual del estudiante
    Route::put('/user/nivel-actual', [EstudianteController::class, 'updateCurrentLevel']);
    //avanza el nivel actual al siguiente
    Route::post('/user/avanzar-nivel', [ProgresoController::class, 'advanceToNextLevel']);

    // Obtener usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
