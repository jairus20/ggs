<?php
class Comment {
    private $idcomment;
    private $description;
    private $fecha;
    private $idpost;
    private $usuario_id;

    public function __construct($description, $idpost, $usuario_id = null) {
        $this->description = $description;
        $this->idpost = $idpost;
        $this->usuario_id = $usuario_id;
        $this->fecha = date('Y-m-d H:i:s'); // Set the current timestamp
    }

    public function getIdComment() {
        return $this->idcomment;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getIdPost() {
        return $this->idpost;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id) {
        $this->usuario_id = $usuario_id;
    }

    public function save($conexion) {
        $description = mysqli_real_escape_string($conexion, $this->description);
        $query = "INSERT INTO comments (description, idpost, fecha, usuario_id) VALUES ('$description', $this->idpost, '$this->fecha', $this->usuario_id)";
        return mysqli_query($conexion, $query);
    }

    public static function bulkInsert($conexion, $comments) {
        if (empty($comments)) return true;
        
        $values = [];
        foreach ($comments as $comment) {
            $description = mysqli_real_escape_string($conexion, $comment->getDescription());
            $idpost = $comment->getIdPost();
            $fecha = $comment->getFecha();
            $usuario_id = $comment->getUsuarioId();
            $values[] = "('$description', $idpost, '$fecha', $usuario_id)";
        }
        
        $query = "INSERT INTO comments (description, idpost, fecha, usuario_id) VALUES " . implode(", ", $values);
        return $conexion->query($query);
    }

    public static function deleteAll($conexion) {
        $query = "DELETE FROM comments";
        return $conexion->query($query);
    }

    public static function resetAutoIncrement($conexion) {
        $query = "ALTER TABLE comments AUTO_INCREMENT = 1";
        return $conexion->query($query);
    }
}
?>