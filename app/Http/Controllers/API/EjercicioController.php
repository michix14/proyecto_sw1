<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ejercicio;
use Illuminate\Http\Request;

class EjercicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Ejercicio::with('leccion')->get(), 200);  //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leccion_id' => 'required|exists:lecciones,id',
            'pregunta_texto' => 'nullable|string',
            'pregunta_audio' => 'nullable|string',
            'respuesta_texto' => 'nullable|string',
            'respuesta_audio' => 'nullable|string',
            'dificultad' => 'required|in:easy,medium,hard',
        ]);

        if (empty($validated['pregunta_texto']) && empty($validated['pregunta_audio'])) {
            return response()->json(['error' => 'Al menos una pregunta_texto o pregunta_audio es requerida.'], 422);
        }

        if (empty($validated['respuesta_texto']) && empty($validated['respuesta_audio'])) {
            return response()->json(['error' => 'Al menos una respuesta_texto o respuesta_audio es requerida.'], 422);
        }

        $ejercicio = Ejercicio::create($validated);
        return response()->json($ejercicio, 201); //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ejercicio = Ejercicio::with('leccion')->find($id);
        if (!$ejercicio) {
            return response()->json(['error' => 'ejercicio no encontrado'], 404);
        }
        return response()->json($ejercicio, 200); //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ejercicio = Ejercicio::find($id);
        if (!$ejercicio) {
            return response()->json(['error' => 'ejercicio no encontrado'], 404);
        }

        $validated = $request->validate([
            'leccion_id' => 'nullable|exists:lecciones,id',
            'pregunta_texto' => 'nullable|string',
            'pregunta_audio' => 'nullable|string',
            'respuesta_texto' => 'nullable|string',
            'respuesta_audio' => 'nullable|string',
            'dificultad' => 'nullable|in:easy,medium,hard',
        ]);

        if (empty($validated['question_text']) && empty($validated['question_audio'])) {
            return response()->json(['error' => 'Al menos una pregunta_texto o pregunta_audio es requerida.'], 422);
        }

        if (empty($validated['answer_text']) && empty($validated['answer_audio'])) {
            return response()->json(['error' => 'Al menos una respuesta_texto o respuesta_audio es requerida.'], 422);
        }

        $ejercicio->update($validated);
        return response()->json($ejercicio, 200);  //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ejercicio = Ejercicio::find($id);
        if (!$ejercicio) {
            return response()->json(['error' => 'Ejercicio no encontrado'], 404);
        }

        $ejercicio->delete();
        return response()->json(['message' => 'Ejercicio borrado exitosamente'], 200); //
    }
}
