<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler {
    private $secret;
    private $issuer;
    private $audience;
    private $expire;
    private $algorithm;

    public function __construct() {

        $this->secret   = $_ENV['JWT_SECRET'];
        $this->issuer   = $_ENV['JWT_ISSUER'];
        $this->audience = $_ENV['JWT_AUDIENCE'];
        $this->expire   = $_ENV['JWT_EXPIRE'];
        $this->algorithm   = $_ENV['JWT_ALGORITHM'];
    }

    /**
     * Genera un token JWT con el id del usuario.
     */
    public function generateToken($id) {
        $issuedAt = time();
        $expire   = $issuedAt + $this->expire;

        $token = [
            'iss'  => $this->issuer,
            'aud'  => $this->audience,
            'sub'  => $id,
            'iat'  => $issuedAt,
            'exp'  => $expire,
        ];
        return JWT::encode($token, $this->secret, $this->algorithm);
    }

    /**
     * Valida el token JWT.
     */
    public function validateToken($token) {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } 
        catch (\Exception $e) {
            return false;
        }
    }
}

?>