<?php
class Post {
    private $idpost;
    private $title;
    private $description;
    private $fecha;
    private $user_id;

    public function __construct($title, $description, $user_id) {
        $this->title = $title;
        $this->description = $description;
        $this->user_id = $user_id;
        $this->fecha = date('Y-m-d H:i:s'); // Set the current timestamp
    }

    public function getId() {
        return $this->idpost;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setId($idpost) {
        $this->idpost = $idpost;
    }

    public function save($conexion) {
        $query = "INSERT INTO posts (title, description, user_id) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ssi", $this->title, $this->description, $this->user_id);
        return $stmt->execute();
    }

    public function saveWithoutPrepare($conexion) {
        $title = mysqli_real_escape_string($conexion, $this->title);
        $description = mysqli_real_escape_string($conexion, $this->description);
        $query = "INSERT INTO posts (title, description, fecha, usuario_id) VALUES ('$title', '$description', '$this->fecha', $this->user_id)";
        if($conexion->query($query)) {
            $this->idpost = $conexion->insert_id;
            return true;
        }
        return false;
    }

    public static function bulkInsert($conexion, $posts) {
        if (empty($posts)) return true;
        
        $values = [];
        foreach ($posts as $post) {
            $title = mysqli_real_escape_string($conexion, $post->getTitle());
            $description = mysqli_real_escape_string($conexion, $post->getDescription());
            $fecha = $post->getFecha();
            $usuario_id = $post->getUserId();
            $values[] = "('$title', '$description', '$fecha', $usuario_id)";
        }
        
        $query = "INSERT INTO posts (title, description, fecha, usuario_id) VALUES " . implode(", ", $values);
        return $conexion->query($query);
    }

    public static function findAll($conexion) {
        $query = "SELECT * FROM posts ORDER BY fecha DESC";
        $result = $conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function findById($conexion, $idpost) {
        $query = "SELECT * FROM posts WHERE idpost = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $idpost);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function deleteAll($conexion) {
        $query = "DELETE FROM posts";
        return $conexion->query($query);
    }

    public static function resetAutoIncrement($conexion) {
        $query = "ALTER TABLE posts AUTO_INCREMENT = 1";
        return $conexion->query($query);
    }
}
?>