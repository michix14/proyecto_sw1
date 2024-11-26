<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EjercicioIA;
use App\Models\Errores;
use Illuminate\Http\Request;

class EjercicioIAController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(EjercicioIA::with('user')->get(), 200); //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pregunta' => 'required|string',
            'respuesta_correcta' => 'nullable|string',
            'tipo' => 'required|in:grammar,vocabulary,listening,speaking',
            'generado_desde' => 'nullable|array',
        ]);

        $ejercicioIA = EjercicioIA::create($validated);
        return response()->json($ejercicioIA, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ejercicioIA = EjercicioIA::with('user')->find($id);
        if (!$ejercicioIA) {
            return response()->json(['error' => 'ejercicioIA no encontrado'], 404);
        }
        return response()->json($ejercicioIA, 200); ////
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ejercicioIA = EjercicioIA::find($id);
        if (!$ejercicioIA) {
            return response()->json(['error' => 'ejercicioIA no encontrado'], 404);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'pregunta' => 'nullable|string',
            'respuesta_correcta' => 'nullable|string',
            'tipo' => 'nullable|in:grammar,vocabulary,listening,speaking',
            'generado_desde' => 'nullable|array',
        ]);

        $ejercicioIA->update($validated);
        return response()->json($ejercicioIA, 200);  //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ejercicioIA = EjercicioIA::find($id);
        if (!$ejercicioIA) {
            return response()->json(['error' => 'ejercicioIA no encontrado'], 404);
        }

        $ejercicioIA->delete();
        return response()->json(['message' => 'ejercicioIA borrado exitosamente'], 200);//
    }

    //funciones para el rol estudiante
    // Obtener los ejercicios adaptativos del usuario autenticado
    public function getAdaptiveExercises(Request $request)
    {
        $user = $request->user();

        $ejercicioIA = EjercicioIA::where('user_id', $user->id)->get();

        return response()->json($ejercicioIA, 200);
    }

    //funcion para evaluar si la respuesta fue correcta
    public function submitAdaptivo($id, Request $request)
    {
        $ejercicioIA = EjercicioIA::find($id);
        if (!$ejercicioIA) {
            return response()->json(['error' => 'ejercicioIA no encontrado'], 404);
        }

        $validated = $request->validate([
            'respuesta_usuario' => 'required|string',
        ]);

        // Simular la evaluación (puedes usar lógica más avanzada o IA aquí)
        $esCorrecto = trim(strtolower($validated['respuesta_usuario'])) === 
        trim(strtolower($ejercicioIA->correct_answer));
        
        if (!$esCorrecto) {
            // Registrar en la tabla de errores
            Errores::create([
                'user_id' => $request->user()->id,
                'ejercicio_id' => $ejercicioIA->id,
                'error_tipo' => 'Respuesta Incorrecta',
                'detalles' => json_encode([
                    'respuesta_usuario' => $validated['respuesta_usuario'],
                    'respuesta_correcta' => $ejercicioIA->respuesta_texto,
                ]),
            ]);
        }

        return response()->json([
            'ejercicioIA_id' => $ejercicioIA->id,
            'is_correct' => $esCorrecto,
            'respuesta_correcta' => $ejercicioIA->respuesta_correcta
        ], 200);
    }
    
}
