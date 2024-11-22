<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\carritoventa;
use App\Models\cliente;
use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
            'telefono' => ['required', 'integer'],
            'sexo' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            $estudianteRole = Role::where('name', 'estudiante')->first();
            if ($estudianteRole) {
                $user->assignRole($estudianteRole);
            }

            $estudiante = new Estudiante([
                'user_id' => $user->id,
                'nombre' => $validatedData['name'],
                'telefono' => $validatedData['telefono'],
                'sexo' => $validatedData['sexo'],
            ]);
            $estudiante->user()->associate($user);
            $estudiante->save();


            $token = $user->createToken('auth_token')->plainTextToken;

            $DTOResponse = DTOEstudianteRegistered::getResponse($user, $estudiante, $token);

            DB::commit();
            return response()->json($DTOResponse, 201);
        }
        catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
