<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ejercicio;
use App\Models\Errores;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\OpenAIService;

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
    //store antiguo funcional
    /*public function store(Request $request)
    {
        $validated = $request->validate([
            'leccion_id' => 'required|exists:lecciones,id',
            'pregunta_texto' => 'nullable|string',
            'pregunta_audio' => 'nullable|string',
            'respuesta_texto' => 'nullable|string',
            'respuesta_audio' => 'nullable|string',
            'dificultad' => 'required|in:easy,medium,hard',
            'tipo' => 'nullable|in:1,2,3,4,5,6',
            'opciones' => 'nullable|array',
        ]);

        if (empty($validated['pregunta_texto']) && empty($validated['pregunta_audio'])) {
            return response()->json(['error' => 'Al menos una pregunta_texto o pregunta_audio es requerida.'], 422);
        }

        if (empty($validated['respuesta_texto']) && empty($validated['respuesta_audio'])) {
            return response()->json(['error' => 'Al menos una respuesta_texto o respuesta_audio es requerida.'], 422);
        }

        $ejercicio = Ejercicio::create($validated);
        return response()->json($ejercicio, 201); //
    }*/

    public function store(Request $request)
    {
        Log::info('Datos recibidos en el método store:', $request->all());

        try {
            $validated = $request->validate([
                'leccion_id' => 'required|exists:lecciones,id',
                'pregunta_texto' => 'nullable|string',
                'pregunta_audio' => 'nullable|file|mimes:mp3,wav|max:2048', // Solo permite archivos .mp3 o .wav
                'respuesta_texto' => 'nullable|string',
                'respuesta_audio' => 'nullable|string',
                'dificultad' => 'required|in:easy,medium,hard',
                'tipo' => 'nullable|in:1,2,3,4,5,6',
                'opciones' => 'nullable|array',
            ]);

            Log::info('Validación completada:', $validated);

            // Verifica que al menos uno de los campos de pregunta esté presente
            if (empty($validated['pregunta_texto']) && !$request->hasFile('pregunta_audio')) {
                Log::warning('Validación fallida: Falta pregunta_texto o pregunta_audio.');
                return response()->json(['error' => 'Al menos una pregunta_texto o un archivo pregunta_audio (.mp3 o .wav) es requerido.'], 422);
            }

            // Verifica que al menos uno de los campos de respuesta esté presente
            if (empty($validated['respuesta_texto']) && empty($validated['respuesta_audio'])) {
                Log::warning('Validación fallida: Falta respuesta_texto o respuesta_audio.');
                return response()->json(['error' => 'Al menos una respuesta_texto o respuesta_audio es requerida.'], 422);
            }

            // Procesa el archivo de pregunta_audio si existe
            $preguntaAudioPath = $request->file('pregunta_audio')
                ? $request->file('pregunta_audio')->store('audios', 'public')
                : null;

            Log::info('Ruta de pregunta_audio procesada:', ['path' => $preguntaAudioPath]);

            // Crea el ejercicio con los datos validados
            $ejercicio = Ejercicio::create([
                'leccion_id' => $validated['leccion_id'],
                'pregunta_texto' => $validated['pregunta_texto'] ?? null,
                'pregunta_audio' => $preguntaAudioPath,
                'respuesta_texto' => $validated['respuesta_texto'] ?? null,
                'respuesta_audio' => $validated['respuesta_audio'] ?? null,
                'dificultad' => $validated['dificultad'],
                'tipo' => $validated['tipo'] ?? null,
                'opciones' => $validated['opciones'] ?? null,
            ]);

            Log::info('Ejercicio creado con éxito:', $ejercicio->toArray());

            return response()->json(['message' => 'Ejercicio creado con éxito', 'ejercicio' => $ejercicio], 201);
        } catch (\Exception $e) {
            Log::error('Error en el método store:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
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
            'tipo' => 'nullable|in:1,2,3,4,5,6',
            'opciones' => 'nullable|array',
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

    //funciones para el rol estudiante

    // Evaluar la respuesta del estudiante
    public function submit($id, Request $request, OpenAIService $openAIService)
    {
        $ejercicio = Ejercicio::find($id);

        if (!$ejercicio) {
            return response()->json(['error' => 'ejercicio no encontrado'], 404);
        }

        $validated = $request->validate([
            'respuesta_usuario' => 'required|string',
        ]);

        // Verifica si la respuesta del usuario es correcta
        $esCorrecto = trim(strtolower($validated['respuesta_usuario'])) ===
            trim(strtolower($ejercicio->respuesta_texto));

        $errores = [];
        if (!$esCorrecto) {
            // Registrar el error en la base de datos
            Errores::create([
                'user_id' => $request->user()->id,
                'ejercicio_id' => $ejercicio->id,
                'error_tipo' => 'Respuesta Incorrecta',
                'detalles' => json_encode([
                    'respuesta_usuario' => $validated['respuesta_usuario'],
                    'respuesta_correcta' => $ejercicio->respuesta_texto,
                ]),
            ]);

            // Agregar a los errores para la retroalimentación
            $errores[] = [
                'pregunta' => $ejercicio->pregunta_texto,
                'respuesta_correcta' => $ejercicio->respuesta_texto,
                'respuesta_usuario' => $validated['respuesta_usuario'],
            ];
        }

        // Generar retroalimentación si hubo errores
        $retroalimentacion = count($errores) > 0
            ? $openAIService->generarRetroalimentacion($errores)
            : "¡Excelente trabajo! Todas las respuestas son correctas.";

        return response()->json([
            'ejercicio_id' => $ejercicio->id,
            'es_correcto' => $esCorrecto,
            'retroalimentacion' => $retroalimentacion,
        ], 200);
    }
}
