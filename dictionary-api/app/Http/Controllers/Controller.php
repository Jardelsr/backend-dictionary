<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Dictionary API",
 *     description="Documentação da API de Dicionário",
 *     @OA\Contact(
 *         email="support@coodesh.com"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor Local"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
