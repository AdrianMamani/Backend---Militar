<?php

class Database {
    private $host = "localhost:3306";
    private $username = "root";
    private $password = "";
    private $dbname = "db_militar";
    public $conexion;

    public function __construct() {
        $this->conexion = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        // pra que la bd maneje caracteres especiales correctamente
        $this->conexion->set_charset("utf8");
    }

    public function getConexion() {
        return $this->conexion;
    }
}

?>
