<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Nivel;
use Illuminate\Http\Request;

class NivelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $niveles = Nivel::all();

        return response()->json($niveles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        $nivel = Nivel::create($validated);

        return response()->json($nivel, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $nivel = Nivel::find($id);

        if (!$nivel) {
            return response()->json(['error' => 'Nivel no existe'], 404);
        }

        return response()->json($nivel, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $nivel = Nivel::find($id);

        if (!$nivel) {
            return response()->json(['error' => 'Nivel no existe'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        $nivel->update($validated);

        return response()->json($nivel, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $nivel = Nivel::find($id);

        if (!$nivel) {
            return response()->json(['error' => 'Nivel no encontrado'], 404);
        }

        $nivel->delete();

        return response()->json(['message' => 'Nivel borrado exitosamente'], 200);
    }

    /**
     * Mostrar las lecciones asociadas a un nivel.
     */
    public function showLessons(int $id): \Illuminate\Http\JsonResponse
    {
        $nivel = Nivel::with('leccion')->find($id);

        if (!$nivel) {
            return response()->json(['error' => 'Nivel no encontrado'], 404);
        }

        return response()->json($nivel->leccion, 200);
    }
}
