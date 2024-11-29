<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;

class DTOEstudianteRegistered extends JsonResource
{
    /**
     * Generate the response structure for a registered student.
     */
    public static function getResponse($user, $estudiante, string $token): array
    {
        return [
            'name' => $estudiante->nombre,
            'email' => $user->email,
            'telefono' => $estudiante->telefono,
            'sexo' => $estudiante->sexo,
            'estudiante_id' => $estudiante->id,
            'user_id' => $user->id,
            'accessToken' => $token,
            'tokenType' => 'Bearer',
        ];
    }
}
