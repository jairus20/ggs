<?php
class PostController {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../database/Conexion.php';
        $this->db = new Conexion();
    }

    public function getRecentPosts() {
        $query = "SELECT p.idpost, p.title, p.description, p.fecha, u.name 
                  FROM posts p 
                  JOIN usuario u ON p.usuario_id = u.idusuario 
                  ORDER BY p.fecha DESC";
        return $this->db->ejecutarQuery($query);
    }

    public function getPostById($id) {
        $query = "SELECT p.*, u.name FROM posts p 
                  JOIN usuario u ON p.usuario_id = u.idusuario 
                  WHERE p.idpost = ?";
        $stmt = $this->db->conectarBD()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createPost($title, $description, $userId) {
        $query = "INSERT INTO posts (title, description, usuario_id) VALUES (?, ?, ?)";
        $stmt = $this->db->conectarBD()->prepare($query);
        $stmt->bind_param("ssi", $title, $description, $userId);
        return $stmt->execute();
    }
}
?>