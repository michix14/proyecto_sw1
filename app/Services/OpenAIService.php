<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    /**
     * @var \OpenAI\Client
     */
    protected $client;

    /**
     * Constructor que inicializa el cliente OpenAI con la clave API desde el archivo .env.
     */
    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    /**
     * Genera retroalimentación educativa basada en los errores proporcionados usando la API de OpenAI.
     *
     * @param array $errores
     * @return string
     */
    public function generarRetroalimentacion(array $errores): string
    {
        $mensaje = "Estos son los errores que cometió el usuario:\n";

        foreach ($errores as $error) {
            $mensaje .= "- Pregunta: {$error['pregunta']}\n";
            $mensaje .= "  Respuesta correcta: {$error['respuesta_correcta']}\n";
            $mensaje .= "  Respuesta del usuario: {$error['respuesta_usuario']}\n\n";
        }

        $prompt = $mensaje . "Proporcione una retroalimentación educativa que ayude al usuario a entender los errores y mejorar sus respuestas.";

        // Realizamos la solicitud a la API de OpenAI
        $response = $this->client->completions()->create([
            'model' => 'gpt-4o',
            'prompt' => $prompt,
            'max_tokens' => 500,
        ]);

        return $response['choices'][0]['text'];
    }
}
