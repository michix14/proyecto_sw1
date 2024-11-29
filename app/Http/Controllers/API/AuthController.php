<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle user login and token generation.
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Check if the user has an admin role
        if ($user->hasRole('admin')) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Hi ' . $user->name,
                'accessToken' => $token,
                'tokenType' => 'Bearer',
                'user' => $user,
                'role' => 'admin',
            ], 200);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $estudiante = Estudiante::where('user_id', $user->id)->first();

        return response()->json([
            'message' => 'Hi ' . $user->name,
            'accessToken' => $token,
            'tokenType' => 'Bearer',
            'user' => $user,
            'estudiante_id' => $estudiante->id,
        ], 200);
    }

    /**
     * Handle user logout and token revocation.
     */
    public function logout(): array
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out, and the token was successfully deleted',
        ];
    }

    /**
     * Validate the provided Bearer token using Sanctum.
     */
    public function validateToken(): \Illuminate\Http\JsonResponse
    {
        if (Auth::guard('sanctum')->check()) {
            return response()->json(['response' => true], 200);
        }

        return response()->json(['response' => false], 401);
    }
}
