<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ejercicio;
use App\Models\Estudiante;
use App\Models\Leccion;
use App\Models\Nivel;
use App\Models\Progreso;
use Illuminate\Http\Request;

class ProgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $progresos = Progreso::with(['user', 'leccion'])->get();

        return response()->json($progresos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leccion_id' => 'required|exists:lecciones,id',
            'completado' => 'required|boolean',
            'completado_fecha' => 'nullable|date',
        ]);

        $progreso = Progreso::create($validated);

        return response()->json($progreso, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $progreso = Progreso::with(['user', 'leccion'])->find($id);

        if (!$progreso) {
            return response()->json(['error' => 'Progreso no encontrado'], 404);
        }

        return response()->json($progreso, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $progreso = Progreso::find($id);

        if (!$progreso) {
            return response()->json(['error' => 'Progreso no encontrado'], 404);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'leccion_id' => 'nullable|exists:lecciones,id',
            'completado' => 'nullable|boolean',
            'completado_fecha' => 'nullable|date',
        ]);

        $progreso->update($validated);

        return response()->json($progreso, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $progreso = Progreso::find($id);

        if (!$progreso) {
            return response()->json(['error' => 'Progreso no encontrado'], 404);
        }

        $progreso->delete();

        return response()->json(['message' => 'Progreso borrado exitosamente'], 200);
    }

    /**
     * Consulta el progreso del usuario autenticado.
     */
    public function getProgress(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        $progresos = Progreso::with('leccion')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($progresos, 200);
    }

    /**
     * Marca una lección como completada.
     */
    public function markComplete(int $leccionId, Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $progreso = Progreso::updateOrCreate(
            [
                'user_id' => $user->id,
                'leccion_id' => $leccionId,
            ],
            [
                'completado' => true,
                'completado_fecha' => now(),
            ]
        );

        return response()->json([
            'message' => 'Lección marcada como completada',
            'progreso' => $progreso,
        ], 200);
    }

    /**
     * Ha completado todas las lecciones.
     */
    public function hasCompletedAllLessons(
        int $nivelId,
        Request $request,
        bool $returnAsJson = true
    ): \Illuminate\Http\JsonResponse|bool {
        $user = $request->user();

        $leccionesTotales = Leccion::where('nivel_id', $nivelId)->count();

        $leccionesCompletadas = Progreso::where('user_id', $user->id)
            ->whereIn('leccion_id', Leccion::where('nivel_id', $nivelId)->pluck('id'))
            ->where('completado', true)
            ->count();

        $haCompletadoTodo = $leccionesCompletadas >= $leccionesTotales;

        if ($returnAsJson) {
            return response()->json([
                'nivel_id' => $nivelId,
                'lecciones_completadas' => $leccionesCompletadas,
                'lecciones_totales' => $leccionesTotales,
                'ha_completado_todo' => $haCompletadoTodo,
            ], 200);
        }

        return $haCompletadoTodo;
    }

    /**
     * Avanza al siguiente nivel.
     */
    public function advanceToNextLevel(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $estudiante = Estudiante::where('user_id', $user->id)->first();

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        $nivelActualId = $estudiante->nivel_actual_id;
        $haCompletadoNivelActual = $this->hasCompletedAllLessons($nivelActualId, $request, false);

        if (!$haCompletadoNivelActual) {
            return response()->json(['error' => 'No has completado el nivel actual'], 400);
        }

        $siguienteNivel = Nivel::where('id', '>', $nivelActualId)->orderBy('id')->first();

        if (!$siguienteNivel) {
            return response()->json(['message' => 'Felicidades, has completado todos los niveles'], 200);
        }

        $estudiante->update(['nivel_actual_id' => $siguienteNivel->id]);

        return response()->json([
            'message' => 'Has avanzado al siguiente nivel',
            'siguiente_nivel' => $siguienteNivel,
        ], 200);
    }

    /**
     * Marca todas las lecciones de un nivel como completadas.
     */
    public function markLevelComplete(int $nivelId, Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $lecciones = Leccion::where('nivel_id', $nivelId)->get();

        if ($lecciones->isEmpty()) {
            return response()->json(['error' => 'Nivel no encontrado o no tiene lecciones'], 404);
        }

        $progresos = [];

        foreach ($lecciones as $leccion) {
            $progreso = Progreso::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'leccion_id' => $leccion->id,
                ],
                [
                    'completado' => true,
                    'completado_fecha' => now(),
                ]
            );

            $progresos[] = $progreso;
        }

        return response()->json([
            'message' => 'Todas las lecciones del nivel marcadas como completadas',
            'nivel_id' => $nivelId,
            'progresos' => $progresos,
        ], 200);
    }
}
