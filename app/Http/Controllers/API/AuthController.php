<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\cliente;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use \stdClass;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        if ($user->hasRole('admin'))
        {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
            'message' => 'Hi '.$user->name,
            'accessToken' => $token,
            'tokenType' => 'Bearer',
            'user' => $user,
            'role' => 'admin'
            ], 200);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $estudiante = Estudiante::where('user_id', $user->id)->first();

        return response()->json([
            'message' => 'Hi '.$user->name,
            'accessToken' => $token,
            'tokenType' => 'Bearer',
            'user' => $user,
            'estudiante_id' => $estudiante->id
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }

    public function validateToken()
    {
        // Validar el token Bearer utilizando Sanctum
        if (Auth::guard('sanctum')->check()) {
            // El token es válido
            return response()->json(['response' => true], 200);
        } else {
            // El token no es válido
            return response()->json(['response' => false], 401);
        }
    }
}
