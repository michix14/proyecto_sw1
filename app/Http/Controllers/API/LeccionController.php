<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Leccion;
use Illuminate\Http\Request;

class LeccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $lecciones = Leccion::with('nivel')->get();

        return response()->json($lecciones, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'nivel_id' => 'required|exists:niveles,id',
        ]);

        $leccion = Leccion::create($validated);

        return response()->json($leccion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $leccion = Leccion::with('nivel')->find($id);

        if (!$leccion) {
            return response()->json(['error' => 'Lección no encontrada'], 404);
        }

        return response()->json($leccion, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $leccion = Leccion::find($id);

        if (!$leccion) {
            return response()->json(['error' => 'Lección no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'nivel_id' => 'required|exists:niveles,id',
        ]);

        $leccion->update($validated);

        return response()->json($leccion, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $leccion = Leccion::find($id);

        if (!$leccion) {
            return response()->json(['error' => 'Lección no encontrada'], 404);
        }

        $leccion->delete();

        return response()->json(['message' => 'Lección borrada exitosamente'], 200);
    }

    /**
     * Obtener los ejercicios de una lección.
     */
    public function getExercises(int $id): \Illuminate\Http\JsonResponse
    {
        $leccion = Leccion::find($id);

        if (!$leccion) {
            return response()->json(['error' => 'Lección no encontrada'], 404);
        }

        $ejercicios = $leccion->ejercicio; // Usa la relación definida en el modelo Leccion

        return response()->json($ejercicios, 200);
    }
}
