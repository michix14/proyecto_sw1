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
    public function index()
    {
        return response()->json(Progreso::with(['user', 'leccion'])->get(), 200); //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leccion_id' => 'required|exists:lecciones,id',
            'completado' => 'required|boolean',
            'completado_fecha' => 'nullable|date',
        ]);

        $progreso = Progreso::create($validated);
        return response()->json($progreso, 201); //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $progreso = Progreso::with(['user', 'leccion'])->find($id);
        if (!$progreso) {
            return response()->json(['error' => 'progreso no encontrado'], 404);
        }
        return response()->json($progreso, 200); //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $progreso = Progreso::find($id);
        if (!$progreso) {
            return response()->json(['error' => 'progreso no encontrado'], 404);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'leccion_id' => 'nullable|exists:lecciones,id',
            'completed' => 'nullable|boolean',
            'completed_at' => 'nullable|date',
        ]);

        $progreso->update($validated);
        return response()->json($progreso, 200); //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $progreso = Progreso::find($id);
        if (!$progreso) {
            return response()->json(['error' => 'progreso no encontrado'], 404);
        }

        $progreso->delete();
        return response()->json(['message' => 'progreso borrado exitosamente'], 200); //
    }

    //funciones para el rol estudiante-----------------------------

    // Consulta el progreso del usuario autenticado
    public function getProgress(Request $request)
    {
        $user = $request->user();

        $progreso = Progreso::with('leccion')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($progreso, 200);
    }

    // Marca una lección como completada
    public function markComplete($leccionId, Request $request)
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
        return response()->json(['message' => 'Leccion marcada como completada', 'progreso' => $progreso], 200);
    }

    //Ha completado todas las lecciones
    /*public function hasCompletedAllLessons($nivelId, Request $request)
    {
        $user = $request->user();

        // Obtener todas las lecciones del nivel
        $leccionesTotales = Leccion::where('nivel_id', $nivelId)->count();

        // Obtener las lecciones completadas por el usuario
        $leccionesCompletadas = Progreso::where('user_id', $user->id)
            ->whereIn('leccion_id', Leccion::where('nivel_id', $nivelId)->pluck('id'))
            ->where('completado', true)
            ->count();

        // Comprobar si todas las lecciones están completadas
        $haCompletadoTodo = $leccionesCompletadas >= $leccionesTotales;
        return response()->json([
            'nivel_id' => $nivelId,
            'lecciones_completadas' => $leccionesCompletadas,
            'lecciones_totales' => $leccionesTotales,
            'ha_completado_todo' => $haCompletadoTodo,
        ], 200);
    }*/

    public function hasCompletedAllLessons($nivelId, Request $request, $returnAsJson = true)
    {
        $user = $request->user();

        // Obtener todas las lecciones del nivel
        $leccionesTotales = Leccion::where('nivel_id', $nivelId)->count();

        // Obtener las lecciones completadas por el usuario
        $leccionesCompletadas = Progreso::where('user_id', $user->id)
            ->whereIn('leccion_id', Leccion::where('nivel_id', $nivelId)->pluck('id'))
            ->where('completado', true)
            ->count();

        // Comprobar si todas las lecciones están completadas
        $haCompletadoTodo = $leccionesCompletadas >= $leccionesTotales;

        // Devolver como JSON si se solicita directamente
        if ($returnAsJson) {
            return response()->json([
                'nivel_id' => $nivelId,
                'lecciones_completadas' => $leccionesCompletadas,
                'lecciones_totales' => $leccionesTotales,
                'ha_completado_todo' => $haCompletadoTodo,
            ], 200);
        }

        // Devolver como booleano si se llama internamente
        return $haCompletadoTodo;
    }


    //Funcion para avanzar al siguiente nivel
    /*public function advanceToNextLevel(Request $request)
    {
        //obtiene usuario
        $user = $request->user();
        $estudiante = Estudiante::where('user_id', $user->id)->first();
        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }
        //verifica que el nivel actual tiene todas las lecciones completadas
        $nivelActualId = $estudiante->nivel_actual_id; // Campo del nivel actual
        $haCompletadoNivelActual = $this->hasCompletedAllLessons($nivelActualId, $request);
        if (!$haCompletadoNivelActual['ha_completado_todo']) {
            return response()->json(['error' => 'No has completado el nivel actual'], 400);
        }

        // Obtener el siguiente nivel
        $siguienteNivel = Nivel::where('id', '>', $nivelActualId)->orderBy('id')->first();
        //si ya no hay siguiente nivel
        if (!$siguienteNivel) {
            return response()->json(['message' => 'Felicidades, has completado todos los niveles'], 200);
        }

        // Actualizar el nivel actual del estudiante
        $estudiante->update(['nivel_actual_id' => $siguienteNivel->id]);
        return response()->json([
            'message' => 'Has avanzado al siguiente nivel',
            'siguiente_nivel' => $siguienteNivel,
        ], 200);
    }*/

    public function advanceToNextLevel(Request $request)
    {
        // Obtiene el usuario
        $user = $request->user();
        $estudiante = Estudiante::where('user_id', $user->id)->first();

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        // Verifica que el nivel actual tiene todas las lecciones completadas
        $nivelActualId = $estudiante->nivel_actual_id; // Campo del nivel actual
        $haCompletadoNivelActual = $this->hasCompletedAllLessons($nivelActualId, $request, false);

        if (!$haCompletadoNivelActual) {
            return response()->json(['error' => 'No has completado el nivel actual'], 400);
        }

        // Obtener el siguiente nivel
        $siguienteNivel = Nivel::where('id', '>', $nivelActualId)->orderBy('id')->first();

        // Si ya no hay siguiente nivel
        if (!$siguienteNivel) {
            return response()->json(['message' => 'Felicidades, has completado todos los niveles'], 200);
        }

        // Actualizar el nivel actual del estudiante
        $estudiante->update(['nivel_actual_id' => $siguienteNivel->id]);

        return response()->json([
            'message' => 'Has avanzado al siguiente nivel',
            'siguiente_nivel' => $siguienteNivel,
        ], 200);
    }


    public function markLevelComplete($nivelId, Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
        // Obtener todas las lecciones del nivel
        $lecciones = Leccion::where('nivel_id', $nivelId)->get();
        if ($lecciones->isEmpty()) {
            return response()->json(['error' => 'Nivel no encontrado o no tiene lecciones'], 404);
        }
        // Marcar todas las lecciones como completadas
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
            $progresos[] = $progreso; // Almacenar progreso para retornar
        }

        return response()->json([
            'message' => 'Todas las lecciones del nivel marcadas como completadas',
            'nivel_id' => $nivelId,
            'progresos' => $progresos,
        ], 200);
    }
}
