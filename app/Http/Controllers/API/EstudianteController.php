<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        // Implementar lógica según sea necesario
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        // Implementar lógica según sea necesario
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        // Implementar lógica según sea necesario
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        // Implementar lógica según sea necesario
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        // Implementar lógica según sea necesario
    }

    /**
     * Consultar la suscripción actual del estudiante.
     */
    public function getSubscription(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        $estudiante = Estudiante::with('suscripcion')
            ->where('user_id', $user->id)
            ->first();

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        return response()->json([
            'suscripcion' => $estudiante->suscripcion,
        ], 200);
    }

    /**
     * Actualizar la suscripción del estudiante.
     */
    public function updateSubscription(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'suscripcion_id' => 'required|exists:suscripcions,id',
        ]);

        $estudiante = Estudiante::where('user_id', $user->id)->first();

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        $estudiante->update(['suscripcion_id' => $validated['suscripcion_id']]);

        return response()->json([
            'message' => 'Suscripción actualizada exitosamente',
            'suscripcion' => $estudiante->suscripcion,
        ], 200);
    }

    /**
     * Actualizar el nivel actual del estudiante.
     */
    public function updateCurrentLevel(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nivel_actual_id' => 'required|exists:niveles,id',
        ]);

        $estudiante = Estudiante::where('user_id', $user->id)->first();

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        $estudiante->update(['nivel_actual_id' => $validated['nivel_actual_id']]);

        return response()->json([
            'message' => 'Nivel actualizado exitosamente',
            'nivel_actual' => $estudiante->nivelActual,
        ], 200);
    }
}
