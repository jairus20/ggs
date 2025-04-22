<?php
require_once '../src/database/Conexion.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $usuario_id = $_SESSION['usuario']['idusuario'];
    $archivo = $_FILES['archivo'];

    // Verificar si hubo un error al subir el archivo
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $nombreOriginal = $archivo['name'];
        $nombreGuardado = uniqid() . '_' . $nombreOriginal;
        $rutaDestino = '../uploads/' . $nombreGuardado;

        // Mover el archivo al directorio de destino
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            $db = new Conexion();
            $rutaRelativa = 'uploads/' . $nombreGuardado;

            // Guardar la información del archivo en la base de datos
            $query = "INSERT INTO archivos (nombre_original, nombre_guardado, ruta, usuario_id) 
                      VALUES ('$nombreOriginal', '$nombreGuardado', '$rutaRelativa', $usuario_id)";
            if ($db->ejecutarQuery($query)) {
                echo "Archivo subido con éxito.";
            } else {
                echo "Error al guardar la información del archivo en la base de datos.";
            }
        }
    } else {
        echo "Error al subir el archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Subir Archivos</h1>
        <form action="upload_file.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="archivo" class="form-label">Selecciona un archivo</label>
                <input type="file" name="archivo" id="archivo" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Archivo</button>
        </form>
        <a href="../index.php" class="btn btn-secondary mt-3">Volver</a>
    </div>
</body>
</html>