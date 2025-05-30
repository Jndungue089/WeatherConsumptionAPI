<?php
namespace App\Http\OpenApi;

/**
 * @OA\Info(
 *     title="Weather Consumption API",
 *     version="1.0.0",
 *     description="API for managing users and fetching weather data with JWTAuth",
 *     @OA\Contact(
 *         email="josemarndungue@gmail.com"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local development server"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="jwt"
 * )
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="city", type="string", example="London", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Swagger
{
}