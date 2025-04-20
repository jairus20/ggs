<?php
class Comment {
    private $idcomment;
    private $description;
    private $fecha;
    private $idpost;

    public function __construct($description, $idpost) {
        $this->description = $description;
        $this->idpost = $idpost;
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

    public function save($conexion) {
        $description = mysqli_real_escape_string($conexion, $this->description);
        $query = "INSERT INTO comments (description, idpost, fecha) VALUES ('$description', $this->idpost, '$this->fecha')";
        return mysqli_query($conexion, $query);
    }
}
?>