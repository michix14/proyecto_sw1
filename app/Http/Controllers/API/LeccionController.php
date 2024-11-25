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
    public function index()
    {
        return response()->json(Leccion::with('nivel')->get(), 200);  //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'nivel_id' => 'required|exists:niveles,id',
        ]);

        $leccion = Leccion::create($validated);
        return response()->json($leccion, 201);//
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $leccion = Leccion::with('nivel')->find($id);
        if (!$leccion) {
            return response()->json(['error' => 'Leccion no encontrada'], 404);
        }
        return response()->json($leccion, 200); //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $leccion = Leccion::find($id);
        if (!$leccion) {
            return response()->json(['error' => 'Leccion no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'nivel_id' => 'required|exists:niveles,id',
        ]);

        $leccion->update($validated);
        return response()->json($leccion, 200); //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leccion = Leccion::find($id);
        if (!$leccion) {
            return response()->json(['error' => 'Leccion no encontrada'], 404);
        }

        $leccion->delete();
        return response()->json(['message' => 'Leccion borrada exitosamente'], 200);
    }

    //funciones rol estudiante
    public function getExercises($id)
    {
        $leccion = Leccion::find($id);

        if (!$leccion) {
            return response()->json(['error' => 'leccion no encontrada'], 404);
        }

        $ejercicios = $leccion->ejercicio; // Usa la relación definida en el modelo Lesson
        return response()->json($ejercicios, 200);
    }
}
