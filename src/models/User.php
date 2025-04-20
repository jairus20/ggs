<?php
class User {
    private $iduser;
    private $email;
    private $name;
    private $habilitado;
    private $password;

    public function __construct($email, $name, $habilitado = 1, $password) {
        $this->email = $email;
        $this->name = $name;
        $this->habilitado = $habilitado;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getId() {
        return $this->iduser;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }

    public function isHabilitado() {
        return $this->habilitado;
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    public function setId($iduser) {
        $this->iduser = $iduser;
    }
}
?>