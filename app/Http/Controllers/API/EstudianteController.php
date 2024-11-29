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

    /**
     * Obtiene los detalles de un estudiante por su ID.
     *
     * @param string $id El ID del estudiante.
     * @return \Illuminate\Http\JsonResponse Respuesta en formato JSON con el estudiante encontrado o un mensaje de error.
     */
    public function getById(string $id): \Illuminate\Http\JsonResponse
    {
        // Buscar el estudiante por ID con las relaciones de suscripción y nivel actual.
        $estudiante = Estudiante::with('suscripcion', 'nivelActual')->find($id);

        // Si el estudiante no se encuentra, devolver un error 404.
        if (!$estudiante) {
            return response()->json(
                ['error' => 'Estudiante no encontrado'],
                404
            );
        }

        // Si se encuentra, devolver la información del estudiante con un mensaje de éxito.
        return response()->json(
            [
                'message' => 'Estudiante encontrado exitosamente',
                'estudiante' => $estudiante,
            ],
            200
        );
    }

    /**
     * Obtiene los detalles de un estudiante por su user_id.
     *
     * @param string $id El user_id del estudiante.
     * @return \Illuminate\Http\JsonResponse Respuesta en formato JSON con el estudiante encontrado o un mensaje de error.
     */
    public function getByUserId(string $id): \Illuminate\Http\JsonResponse
    {
        // Buscar al estudiante por user_id, incluyendo las relaciones con suscripción y nivel actual.
        $estudiante = Estudiante::with('suscripcion', 'nivelActual')
            ->where('user_id', $id)
            ->first();

        // Verificar si el estudiante existe.
        if (!$estudiante) {
            return response()->json(
                ['error' => 'Estudiante no encontrado'],
                404
            );
        }

        // Retornar el estudiante encontrado con un mensaje de éxito.
        return response()->json(
            [
                'message' => 'Estudiante encontrado exitosamente',
                'estudiante' => $estudiante,
            ],
            200
        );
    }
}
