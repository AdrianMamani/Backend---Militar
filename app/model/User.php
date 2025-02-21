<?php

class UserModel {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getConexion();
    }

    public function getAll() {
        $query = "SELECT id_usuario, nombre_usuario, rol FROM Usuario";
        $result = $this->db->query($query);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    public function getById($id) {
        $query = "SELECT id_usuario, nombre_usuario, rol FROM Usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($username, $password, $rol) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO Usuario (nombre_usuario, contrasena, rol) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $username, $hashedPassword,$rol);
        return $stmt->execute();
    }

    public function update($id, $username, $rol) {
        $query = "UPDATE Usuario SET nombre_usuario = ?, rol = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $username, $rol, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM Usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function findByUsername($username) {
        $query = "SELECT * FROM Usuario WHERE nombre_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

?>
