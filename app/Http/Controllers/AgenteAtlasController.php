<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\AgenteAtlasQueryService;


class AgenteAtlasController extends Controller
{
    
    private const GEMINI_MODEL    = 'gemini-2.5-flash-lite';
    private const GEMINI_BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/';

    private const MAX_LOOP = 5;

    private const MAX_RETRIES = 3;

    private const RETRY_SLEEP_BASE = 5;

    public function __construct(
        private readonly AgenteAtlasQueryService $queryService
    ) {}

    private function getSystemInstruction(): string
    {
        return implode(' ', [
            'Eres el Agente IA oficial del Atlas de Infraestructura Escolar de Puebla,',
            'desarrollado para el CAPCEE (Comité Administrador Poblano para la Construcción de Espacios Educativos).',
            '',
            'ROL: Asistente experto en infraestructura educativa del estado de Puebla, México.',
            'Tienes acceso en tiempo real a la base de datos mediante las funciones disponibles.',
            '',
            'REGLAS ESTRICTAS:',
            '1. Responde SIEMPRE en español claro y bien estructurado.',
            '2. Cuando el usuario pregunte datos de obras, planteles o estadísticas,',
            '   DEBES llamar a la función correspondiente antes de responder. NUNCA inventes datos.',
            '3. Presenta los resultados en listas o tablas para mayor legibilidad.',
            '4. Si una consulta no devuelve resultados, indícalo y sugiere ajustar los filtros.',
            '5. Muestra los montos en formato de pesos mexicanos (MXN) cuando corresponda.',
            '6. Si el usuario pregunta por múltiples filtros, aplícalos todos en una sola llamada.',
            '',
            'CONTEXTO TÉCNICO: Sistema Atlas en Laravel (MySQL). Módulos: Gestión de Obra,',
            'Mobiliario, Expediente Digital, Módulo Geo. Roles: Administrador y Analista.',
            '7. Cuando recibas estadísticas agregadas, SIEMPRE menciona los totales, promedios y distribución por estatus.',
            '8. Usa tablas markdown cuando presentes listas de obras o planteles (columnas: CCT, Nombre, Municipio, Avance, Estatus).',
            '9. Si el resultado tiene "resumen_agregado" o "estadisticas_servicios", comienza tu respuesta con ese resumen ANTES de listar ejemplos.',
            '10. Para montos, usa formato legible: "12.5 millones de pesos" en lugar de solo el número.',
        ]);
    }

    private function getFunctionDeclarations(): array
    {
        return [

            [
                'name'        => 'consultar_infraestructura',
                'description' => implode(' ', [
                    'Busca planteles (escuelas) en el Atlas filtrando por sus características de',
                    'infraestructura y servicios básicos: energía solar, sin energía eléctrica,',
                    'acceso a internet, agua de red pública, cisterna, drenaje público o fosa séptica.',
                    'También permite filtrar por nombre de municipio o ID de macroregión.',
                    'Devuelve el total encontrado y un máximo de 10 ejemplos representativos.',
                ]),
                'parameters' => [
                    'type'       => 'OBJECT',
                    'properties' => [
                        'energia_solar' => [
                            'type'        => 'BOOLEAN',
                            'description' => 'Si es true, filtra solo planteles con paneles solares instalados.',
                        ],
                        'sin_energia' => [
                            'type'        => 'BOOLEAN',
                            'description' => 'Si es true, filtra planteles que NO tienen ningún servicio eléctrico.',
                        ],
                        'internet' => [
                            'type'        => 'BOOLEAN',
                            'description' => 'Si es true, filtra planteles con acceso a internet.',
                        ],
                        'agua_red_publica' => [
                            'type'        => 'BOOLEAN',
                            'description' => 'Si es true, filtra planteles con conexión a la red pública de agua.',
                        ],
                        'cisterna' => [
                            'type'        => 'BOOLEAN',
                            'description' => 'Si es true, filtra planteles que cuentan con cisterna.',
                        ],
                        'drenaje_publico' => [
                            'type'        => 'BOOLEAN',
                            'description' => 'Si es true, filtra planteles conectados al drenaje público municipal.',
                        ],
                        'fosa_septica' => [
                            'type'        => 'BOOLEAN',
                            'description' => 'Si es true, filtra planteles que usan fosa séptica como sistema de drenaje.',
                        ],
                        'municipio' => [
                            'type'        => 'STRING',
                            'description' => 'Nombre parcial del municipio para filtrar (ej: "Teziutlán", "Izúcar").',
                        ],
                        'macroregion_id' => [
                            'type'        => 'INTEGER',
                            'description' => 'ID numérico de la macroregión para filtrar los planteles.',
                        ],
                    ],
                    'required' => [],
                ],
            ],

            [
                'name'        => 'consultar_obras_capcee',
                'description' => implode(' ', [
                    'Busca proyectos de inversión (obras) del CAPCEE en la base de datos.',
                    'Permite filtrar por: tipo de obra o módulo (ej: "aulas", "sanitarios", "techado"),',
                    'año de inicio, clave CCT específica de un plantel, nombre del municipio',
                    'o ID de macroregión. Devuelve avances físico y financiero, montos y estatus.',
                ]),
                'parameters' => [
                    'type'       => 'OBJECT',
                    'properties' => [
                        'tipo_obra' => [
                            'type'        => 'STRING',
                            'description' => 'Tipo o módulo de la obra (busca en `modulo` y `nombre_proyecto`). Ej: "aulas", "sanitarios", "bardeo", "techado".',
                        ],
                        'anio_inicio' => [
                            'type'        => 'INTEGER',
                            'description' => 'Año de inicio de la obra (ej: 2022, 2023, 2024).',
                        ],
                        'cct' => [
                            'type'        => 'STRING',
                            'description' => 'Clave de Centro de Trabajo exacta del plantel (ej: "21DPR0001A").',
                        ],
                        'municipio' => [
                            'type'        => 'STRING',
                            'description' => 'Nombre parcial del municipio donde se ejecutó la obra (ej: "Puebla", "Tehuacán").',
                        ],
                        'macroregion_id' => [
                            'type'        => 'INTEGER',
                            'description' => 'ID numérico de la macroregión para filtrar las obras.',
                        ],
                    ],
                    'required' => [],
                ],
            ],

            [
                'name'        => 'resumen_estadisticas',
                'description' => implode(' ', [
                    'Devuelve un resumen estadístico global del Atlas: total de planteles registrados,',
                    'total de proyectos de inversión, conteo de proyectos agrupados por estatus',
                    '(Licitación, Contratación, Ejecución, Cierre, etc.),',
                    'avance físico y financiero promedio, y monto total contratado.',
                    'Úsalo cuando el usuario pregunte por cifras generales, totales o un panorama general del sistema.',
                ]),
                'parameters' => [
                    'type'       => 'OBJECT',
                    'properties' => new \stdClass(), // Sin parámetros
                    'required'   => [],
                ],
            ],

            // En getFunctionDeclarations(), agrega:
            [
                'name'        => 'resumen_por_filtro',
                'description' => 'Devuelve estadísticas agregadas (avances, montos, distribución por estatus y tipo de obra) filtradas por macroregión, municipio o año. Úsalo cuando el usuario pregunte cómo va una zona o periodo específico.',
                'parameters'  => [
                    'type'       => 'OBJECT',
                    'properties' => [
                        'macroregion_id' => ['type' => 'INTEGER', 'description' => 'ID de macroregión'],
                        'municipio'      => ['type' => 'STRING',  'description' => 'Nombre parcial del municipio'],
                        'anio_inicio'    => ['type' => 'INTEGER', 'description' => 'Año de inicio (ej: 2023)'],
                    ],
                    'required' => [],
                ],
            ],

            

        ];
    }

    private function executeFunction(string $name, array $args): array 
    {
        try {
            return match ($name) { 
                'consultar_infraestructura' => $this->queryService->consultarInfraestructura($args),
                'consultar_obras_capcee'    => $this->queryService->consultarObrasCapcee($args),
                'resumen_estadisticas'      => $this->queryService->resumenEstadisticas(),
                'resumen_por_filtro'        => $this->queryService->resumenPorFiltro($args), 
                default                     => ['error' => "Función desconocida: {$name}"],
            };
        } catch (\Throwable $e) {
            Log::error("AgenteAtlas: Error en función [{$name}]", [
                'args'    => $args,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return ['error' => 'Error al consultar la base de datos. Intenta reformular la pregunta.'];
        }
    }

    private function callGemini(array $contents, string $apiKey): array
    {
        $url = self::GEMINI_BASE_URL . self::GEMINI_MODEL . ':generateContent?key=' . $apiKey;

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $this->getSystemInstruction()]],
            ],
            'contents' => $contents,
            'tools'    => [
                ['function_declarations' => $this->getFunctionDeclarations()],
            ],
            'tool_config' => [
                'function_calling_config' => [
                    'mode' => 'AUTO',  
                ],
            ],
            'generationConfig' => [
                'maxOutputTokens' => 2048,
                'temperature'     => 0.2,
                'topP'            => 0.9,
            ],
        ];

        $lastException = null;

        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            try {
                $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(60)
                    ->post($url, $payload);

                if ($response->successful()) {
                    return $response->json();
                }

                if ($response->status() === 429) {
                    $sleepSeconds = self::RETRY_SLEEP_BASE * $attempt;
                    Log::warning("AgenteAtlas: 429 Too Many Requests. Reintento {$attempt}/" . self::MAX_RETRIES . ". Esperando {$sleepSeconds}s.");
                    sleep($sleepSeconds);
                    continue;
                }

                Log::error('AgenteAtlas: Error HTTP de Gemini', [
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                    'attempt' => $attempt,
                ]);

                throw new \RuntimeException(
                    "Error de la API de Gemini [{$response->status()}]: " . $response->body()
                );

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $lastException = $e;
                Log::warning("AgenteAtlas: Timeout/conexión en intento {$attempt}. {$e->getMessage()}");
                sleep(self::RETRY_SLEEP_BASE * $attempt);
            }
        }

        throw new \RuntimeException(
            'No se pudo conectar con la API de Gemini después de ' . self::MAX_RETRIES . ' intentos.',
            0,
            $lastException
        );
    }

    private function toGeminiContents(array $messages): array
    {
        $contents = [];

        foreach ($messages as $msg) {
            $role    = $msg['role'] === 'assistant' ? 'model' : 'user';
            $content = $msg['content'];

            if (is_string($content)) {
                $contents[] = [
                    'role'  => $role,
                    'parts' => [['text' => $content]],
                ];
            } elseif (is_array($content)) {
                $contents[] = [
                    'role'  => $role,
                    'parts' => $content,
                ];
            }
        }

        return $contents;
    }

    public function chat(Request $request): JsonResponse
    {
        set_time_limit(120);
        
        $request->validate([
            'messages'           => ['required', 'array', 'min:1', 'max:40'],
            'messages.*.role'    => ['required', 'string', 'in:user,assistant'],
            'messages.*.content' => ['required'],
        ]);

        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            return response()->json([
                'reply' => '⚠️ Servicio no configurado. Agrega GEMINI_API_KEY en el archivo .env del servidor.',
            ], 503);
        }

        try {
            $contents = $this->toGeminiContents($request->input('messages'));

            for ($iteration = 0; $iteration < self::MAX_LOOP; $iteration++) {

                $data      = $this->callGemini($contents, $apiKey);
                $candidate = $data['candidates'][0]       ?? null;
                $parts     = $candidate['content']['parts'] ?? [];
                $finishReason = $candidate['finishReason']  ?? 'STOP';

                if (config('app.debug')) {
                    Log::debug("AgenteAtlas: Iteración {$iteration} | finishReason: {$finishReason}", [
                        'parts_count' => count($parts),
                    ]);
                }

                // 1. PRIMERO evaluamos si es una llamada a función
                $hasFunctionCall = $finishReason === 'FUNCTION_CALL'
                    || collect($parts)->contains(fn ($p) => isset($p['functionCall']));

                if ($hasFunctionCall) {
                    
                    // Agregamos la petición de función al historial
                    $contents[] = [
                        'role'  => 'model',
                        'parts' => $parts,
                    ];

                    $functionResponses = [];

                    foreach ($parts as $part) {
                        if (!isset($part['functionCall'])) {
                            continue;
                        }

                        $fnName = $part['functionCall']['name'];
                        $fnArgs = $part['functionCall']['args'] ?? [];
                        // Aseguramos que sea un arreglo, incluso si Gemini mandó un objeto stdClass vacío
                        $safeArgs = is_object($fnArgs) ? json_decode(json_encode($fnArgs), true) : (array) $fnArgs;
                        
                        Log::info("AgenteAtlas: Ejecutando función [{$fnName}]", ['args' => $safeArgs]);
                        // Ejecutamos la consulta en la base de datos
                        $resultArray = $this->executeFunction($fnName, $safeArgs);

                        $functionResponses[] = [
                            'functionResponse' => [
                                'name'     => $fnName,
                                'response' => $resultArray, 
                            ],
                        ];
                    }

                    // Devolvemos el resultado de la BD a Gemini simulando ser el usuario
                    $contents[] = [
                        'role'  => 'user',
                        'parts' => $functionResponses,
                    ];

                    // Importante: Saltamos a la siguiente iteración para enviarle los datos a Gemini
                    continue; 
                }

                // 2. DESPUÉS evaluamos si Gemini generó texto y se detuvo
                if ($finishReason === 'STOP') {
                    $textParts = array_filter($parts, fn ($p) => isset($p['text']));
                    $text      = implode("\n", array_column($textParts, 'text'));

                    return response()->json([
                        'reply' => trim($text) ?: 'No se generó una respuesta. Por favor reformula tu pregunta.',
                    ]);
                }

                // 3. Evaluamos límites de tokens
                if ($finishReason === 'MAX_TOKENS') {
                    $textParts = array_filter($parts, fn ($p) => isset($p['text']));
                    $text      = implode("\n", array_column($textParts, 'text'));

                    return response()->json([
                        'reply' => (trim($text) ?: 'La respuesta fue truncada por límite de tokens.')
                            . "\n\n_(Nota: la respuesta fue cortada. Puedes pedir más detalles.)_",
                    ]);
                }

                // Si llegamos aquí, es un motivo de finalización desconocido
                Log::warning("AgenteAtlas: finishReason inesperado [{$finishReason}]. Terminando loop.", [
                    'parts'     => $parts,
                    'candidate' => $candidate,  
                ]);

                $textParts = array_filter($parts, fn ($p) => isset($p['text']));
                $text      = implode("\n", array_column($textParts, 'text'));

                return response()->json([
                    'reply' => trim($text) ?: 'No pude procesar esa consulta. Intenta con una pregunta más específica.',
                ]);
                break;
            }

            return response()->json([
                'reply' => 'No se pudo completar la consulta después de varias iteraciones. Por favor intenta de nuevo.',
            ]);

        } catch (\Throwable $e) {
            Log::error('AgenteAtlas: Error en chat()', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'reply' => 'Ocurrió un error al procesar tu consulta. Por favor intenta de nuevo en unos momentos.',
            ], 500);
        }
    }
}
