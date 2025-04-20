<?php
class UserController {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../database/Conexion.php';
        $this->db = new Conexion();
    }

    public function register($email, $name, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO usuario (email, name, password) VALUES ('$email', '$name', '$hashedPassword')";
        return $this->db->ejecutarQuery($query);
    }

    public function login($email, $password) {
        $query = "SELECT * FROM usuario WHERE email = '$email'";
        $result = $this->db->ejecutarQuery($query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return null;
    }

    public function getUserById($id) {
        $query = "SELECT * FROM usuario WHERE idusuario = $id";
        $result = $this->db->ejecutarQuery($query);
        return mysqli_fetch_assoc($result);
    }
}
?>