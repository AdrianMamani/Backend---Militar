<?php
require_once '../../config/Database.php';

class InterfazModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function subirGaleria($tipo_archivo, $ruta_archivo, $informacion) {
        $sql = "INSERT INTO Galeria (tipo_archivo, ruta_archivo, informacion) VALUES (?, ?, ?)";
        $stmt = $this->db->getConexion()->prepare($sql);
        return $stmt->execute([$tipo_archivo, $ruta_archivo, $informacion]);
    }

    public function actualizarInformacion($id_promocion, $datos) {
        $sql = "UPDATE Promocion SET descripcion = ?, resenia = ? WHERE id_promocion = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        return $stmt->execute([$datos['descripcion'], $datos['resenia'], $id_promocion]);
    }

    public function actualizarGaleria($id_galeria, $ruta_archivo, $tipo) {
        $sql = "UPDATE Galeria SET ruta_archivo = ?, tipo_archivo = ? WHERE id_galeria = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        return $stmt->execute([$ruta_archivo, $tipo, $id_galeria]);
    }

    public function subirVideo($tipo_archivo, $ruta_archivo, $informacion) {
        $sql = "INSERT INTO Galeria (tipo_archivo, ruta_archivo, informacion) VALUES (?, ?, ?)";
        $stmt = $this->db->getConexion()->prepare($sql);
        return $stmt->execute([$tipo_archivo, $ruta_archivo, $informacion]);
    }

    public function actualizarInformacionVideo($id_galeria, $informacion) {
        $sql = "UPDATE Galeria SET informacion = ? WHERE id_galeria = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        return $stmt->execute([$informacion, $id_galeria]);
    }

    public function editarImagenReferencia($id_galeria, $ruta_archivo) {
        $sql = "UPDATE Galeria SET ruta_archivo = ? WHERE id_galeria = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        return $stmt->execute([$ruta_archivo, $id_galeria]);
    }
}
