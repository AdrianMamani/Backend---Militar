<?php
class Galeria
{

    private $db;

    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }
}
