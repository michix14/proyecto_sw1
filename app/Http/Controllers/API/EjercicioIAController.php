<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EjercicioIA;
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
    public function destroy(string $id)
    {
        $ejercicioIA = EjercicioIA::find($id);
        if (!$ejercicioIA) {
            return response()->json(['error' => 'ejercicioIA no encontrado'], 404);
        }

        $ejercicioIA->delete();
        return response()->json(['message' => 'ejercicioIA borrado exitosamente'], 200);//
    }
}
