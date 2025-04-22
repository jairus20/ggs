<?php
require_once '../src/database/Conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $usuario_id = isset($_SESSION['usuario']['idusuario']) ? intval($_SESSION['usuario']['idusuario']) : 0;
    $archivoRuta = null;

    // Verificar que el usuario esté logueado
    if ($usuario_id <= 0) {
        header('Location: ../public/login.php');
        exit;
    }

    // Manejar la subida del archivo
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivo'];
        $nombreOriginal = basename($archivo['name']);
        $nombreGuardado = uniqid() . '_' . $nombreOriginal;
        $rutaDestino = '../uploads/' . $nombreGuardado;

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            $archivoRuta = 'uploads/' . $nombreGuardado;
        } else {
            $archivoRuta = null; // Si falla, no se guarda la ruta
        }
    }

    if (!empty($title) && !empty($description)) {
        $db = new Conexion();
        
        $title = mysqli_real_escape_string($db->getConnection(), $title);
        $description = mysqli_real_escape_string($db->getConnection(), $description);
        $archivoRuta = $archivoRuta ? mysqli_real_escape_string($db->getConnection(), $archivoRuta) : null;
        
        $query = "INSERT INTO posts (title, description, archivo, usuario_id) 
                  VALUES ('$title', '$description', '$archivoRuta', $usuario_id)";
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