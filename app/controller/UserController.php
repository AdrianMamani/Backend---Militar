<?php
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../utils/JwtHandler.php";
class UserController
{
    private $userModel;

    public function __construct(Database $db)
    {
        $this->userModel = new UserModel($db);
    }

    // Obtener todos los usuarios
    public function getAll()
    {
        $users = $this->userModel->getAll();
        Response::json($users);
    }

    // Obtener un usuario 
    public function getById($id)
    {
        $user = $this->userModel->getById($id);
        if ($user) {
            Response::json($user);
        } else {
            Response::json(['error' => 'Usuario no encontrado'], 404);
        }
    }

    // Crear un nuevo usuario (Esto no se Usara, solo para pruebas)
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username'], $data['rol'], $data['password'])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->userModel->create($data['username'], $data['password'], $data['rol']);

        if ($result) {
            Response::json(['message' => 'Usuario creado exitosamente']);
        } else {
            Response::json(['error' => 'Error al crear usuario'], 500);
        }
    }

    // Actualizar usuario
    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username'], $data['email'])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->userModel->update($id, $data['username'], $data['email']);

        if ($result) {
            Response::json(['message' => 'Usuario actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar usuario'], 500);
        }
    }

    // Eliminar usuario
    public function delete($id)
    {
        $result = $this->userModel->delete($id);

        if ($result) {
            Response::json(['message' => 'Usuario eliminado']);
        } else {
            Response::json(['error' => 'Error al eliminar usuario'], 500);
        }
    }

    // Login
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['username'], $data['password'])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $user = $this->userModel->findByUsername($data['username']);

        if ($user && password_verify($data['password'], $user['contrasena'])) {
            $jwtHandler = new JWTHandler();
            $token = $jwtHandler -> generateToken($user['id_usuario']);
            Response::json(['message' => 'Login exitoso', 'token' => $token]);
        } else {
            Response::json(['error' => 'Credenciales incorrectas'], 401);
        }
    }

    // Registro
    public function register()
    {
        $this->create();
    }

    // Logout (solo informativo, no se maneja sesiones aquí)
    public function logout()
    {
        Response::json(['message' => 'Logout exitoso']);
    }
}
