<?php

class Database
{
    public $conexion;

    public function __construct()
    {

        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $host = $_ENV['DB_HOST'];
        
        $this->conexion = new mysqli($host, $username, $password, $dbname);

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        // pra que la bd maneje caracteres especiales correctamente
        $this->conexion->set_charset("utf8");
    }

    public function getConexion()
    {
        return $this->conexion;
    }
}
