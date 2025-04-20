<?php
require_once '../src/database/Conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $usuario_id = isset($_SESSION['usuario']['idusuario']) ? intval($_SESSION['usuario']['idusuario']) : 0;

    // Verificar que el usuario esté logueado
    if ($usuario_id <= 0) {
        header('Location: ../public/login.php');
        exit;
    }

    if (!empty($title) && !empty($description)) {
        $db = new Conexion();
        
        $title = mysqli_real_escape_string($db->getConnection(), $title);
        $description = mysqli_real_escape_string($db->getConnection(), $description);
        
        $query = "INSERT INTO posts (title, description, usuario_id) 
                  VALUES ('$title', '$description', $usuario_id)";
        $result = $db->ejecutarQuery($query);
        
        if ($result) {
            header('Location: ../index.php');
            exit;
        }
    }
}

// Si hay un error o no se proporcionaron datos correctos, redirigir a la página principal
header('Location: ../index.php');
?>

<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <script src="./assets/theme.js" defer></script>
</head>
<body>
</body>
</html>