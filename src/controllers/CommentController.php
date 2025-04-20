<?php
class CommentController {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../database/Conexion.php';
        $this->db = new Conexion();
    }

    public function addComment($idpost, $description) {
        if ($idpost > 0 && !empty($description)) {
            $description = mysqli_real_escape_string($this->db->conectarBD(), $description);
            $query = "INSERT INTO comments (description, idpost) VALUES ('$description', $idpost)";
            return $this->db->ejecutarQuery($query);
        }
        return false;
    }

    public function getComments($idpost) {
        $query = "SELECT * FROM comments WHERE idpost = $idpost ORDER BY fecha DESC";
        return $this->db->ejecutarQuery($query);
    }
}
?>