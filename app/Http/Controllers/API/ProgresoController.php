<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        return response()->json($progreso, 201);//
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
        return response()->json($progreso, 200);//
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
    
    
}
