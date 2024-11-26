<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //funciones para el estudiante
    // Consultar la suscripción actual del estudiante
    public function getSubscription(Request $request)
    {
        $user = $request->user();

        $estudiante = Estudiante::with('suscripcion')->where('user_id', $user->id)->first();

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        return response()->json([
            'suscripcion' => $estudiante->suscripcion,
        ], 200);
    }

    // Actualizar la suscripción del estudiante
    public function updateSubscription(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'suscripcion_id' => 'required|exists:suscripcions,id',
        ]);

        $estudiante = Estudiante::where('user_id', $user->id)->first();

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        $estudiante->update(['subscripcion_id' => $validated['subscripcion_id']]);

        return response()->json([
            'message' => 'Suscripción actualizada exitosamente',
            'suscripcion' => $estudiante->suscription,
        ], 200);
    }
}
