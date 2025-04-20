<?php
require_once 'Conexion.php';

$db = new Conexion();

// Crear el usuario inicial si no existe
$email = 'holi@mispostsgrupo5.com';
$name = 'holi';
$habilitado = 1;
$password = password_hash('holi', PASSWORD_BCRYPT); // Contraseña encriptada

$queryCheck = "SELECT * FROM usuario WHERE email = '$email'";
$result = $db->ejecutarQuery($queryCheck);

if ($result && mysqli_num_rows($result) > 0) {
    echo "El usuario administrador ya existe.";
} else {
    $queryInsert = "INSERT INTO usuario (email, name, habilitado, password) 
                    VALUES ('$email', '$name', $habilitado, '$password')";
    if ($db->ejecutarQuery($queryInsert)) {
        echo "Usuario administrador creado con éxito.";
    } else {
        echo "Error al crear el usuario administrador: " . $db->getError();
    }
}

$db->cerrarConexion();