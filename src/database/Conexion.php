<?php

class Conexion {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'misposts';
    private $connection;

    public function getConnection() {
        return $this->connection;
    }
    
    public function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die('Error de conexión: ' . $this->connection->connect_error);
        }
    }

    public function ejecutarQuery($query) {
        return $this->connection->query($query);
    }

    public function getError() {
        return $this->connection->error;
    }

    public function cerrarConexion() {
        $this->connection->close();
    }
}
?>