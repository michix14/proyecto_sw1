<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Suscripcion::all(), 200); //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
            'caracteristica' => 'nullable|json',
        ]);

        $suscripcion = Suscripcion::create($validated);
        return response()->json($suscripcion, 201);//
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $suscripcion = Suscripcion::find($id);
        if (!$suscripcion) {
            return response()->json(['error' => 'Subscripcion no encontrada'], 404);
        }
        return response()->json($suscripcion, 200);//
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $suscripcion = Suscripcion::find($id);
        if (!$suscripcion) {
            return response()->json(['error' => 'Subscripcion no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'nullable|string|max:50',
            'precio' => 'nullable|numeric|min:0',
            'caracteristica' => 'nullable|json',
        ]);

        $suscripcion->update($validated);
        return response()->json($suscripcion, 200);//
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $suscripcion = Suscripcion::find($id);
        if (!$suscripcion) {
            return response()->json(['error' => 'Subscripcion no encontrada'], 404);
        }

        $suscripcion->delete();
        return response()->json(['message' => 'Subscription borrada exitosamente'], 200);//
    }
}
