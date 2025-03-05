<?php
require_once __DIR__ . '/../utils/JwtHandler.php';
require_once __DIR__ . '/../utils/Response.php';

class AuthMiddleware
{
    public static function handle()
    {
        $headers = getallheaders();
        
        if (!isset($headers['Authorization'])) {
            Response::json(['error' => 'No se proporcionó token de autenticación'], 401);
        }
        
        $authHeader = $headers['Authorization'];
        if (strpos($authHeader, 'Bearer ') !== 0) {
            Response::json(['error' => 'Formato de token inválido'], 401);
        }
        
        $token = substr($authHeader, 7); // Eliminar "Bearer " del inicio
        $jwtHandler = new JWTHandler();
        $decoded = $jwtHandler->validateToken($token);
        if (!$decoded) {
            Response::json(['error' => 'Token inválido o expirado'], 401);
        }
        
        return $decoded;
    }
}