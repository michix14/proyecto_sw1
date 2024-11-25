<<?php

    use Illuminate\Support\Facades\Route;

    /*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran rutas web para tu aplicación.
| En este caso, eliminamos dependencias de vistas y assets como Vite.
|
*/

    // Ruta principal que responde con un mensaje JSON simple
    Route::get('/', function () {
        return response()->json(['message' => 'API is working'], 200);
    });

    // Grupo de rutas protegidas por middleware (autenticación)
    Route::middleware([
        'auth:sanctum',
        'verified',
    ])->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Dashboard está funcionando'], 200);
        })->name('dashboard');
    });
