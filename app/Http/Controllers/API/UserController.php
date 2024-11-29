<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Store a newly created user and associate it with a student.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],
            'telefono' => ['required', 'integer'],
            'sexo' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // Crear el usuario
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Asignar el rol de estudiante
            $estudianteRole = Role::where('name', 'estudiante')->first();
            if ($estudianteRole) {
                $user->assignRole($estudianteRole);
            }

            // Crear la instancia de estudiante
            $estudiante = Estudiante::create([
                'user_id' => $user->id,
                'nombre' => $validatedData['name'],
                'telefono' => $validatedData['telefono'],
                'sexo' => $validatedData['sexo'],
            ]);

            // Generar el token de autenticación
            $token = $user->createToken('auth_token')->plainTextToken;

            // Crear la respuesta DTO
            $DTOResponse = DTOEstudianteRegistered::getResponse($user, $estudiante, $token);

            DB::commit();

            return response()->json($DTOResponse, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el usuario: ' . $e->getMessage()], 500);
        }
    }
}

