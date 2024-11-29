<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Errores;
use Illuminate\Http\Request;

class ErroresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $errores = Errores::with(['user', 'ejercicio'])->get();

        return response()->json($errores, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ejercicio_id' => 'required|exists:ejercicios,id',
            'error_tipo' => 'nullable|string',
            'detalles' => 'nullable|array',
        ]);

        $errores = Errores::create($validated);

        return response()->json($errores, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $errores = Errores::with(['user', 'ejercicio'])->find($id);

        if (!$errores) {
            return response()->json(['error' => 'Error no encontrado'], 404);
        }

        return response()->json($errores, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $errores = Errores::find($id);

        if (!$errores) {
            return response()->json(['error' => 'Error no encontrado'], 404);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'ejercicio_id' => 'nullable|exists:ejercicios,id',
            'error_tipo' => 'nullable|string',
            'detalles' => 'nullable|array',
        ]);

        $errores->update($validated);

        return response()->json($errores, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $errores = Errores::find($id);

        if (!$errores) {
            return response()->json(['error' => 'Error no encontrado'], 404);
        }

        $errores->delete();

        return response()->json(['message' => 'Error borrado exitosamente'], 200);
    }

    /**
     * Obtener los errores del usuario autenticado.
     */
    public function getErrors(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        $errores = Errores::with('ejercicio')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($errores, 200);
    }
}
