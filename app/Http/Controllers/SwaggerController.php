<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API de Chamados",
 *     version="1.0.0",
 *     description="Documentação da API de Chamados"
 * )
 * @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *     )
 */
class SwaggerController extends BaseController
{
    // Este controlador pode ser deixado vazio, pois só serve para definir as anotações do Swagger
}
