<?php
require_once '../src/database/Conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpost = isset($_POST['idpost']) ? intval($_POST['idpost']) : 0;
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $usuario_id = isset($_SESSION['usuario']['idusuario']) ? intval($_SESSION['usuario']['idusuario']) : 0;

    // Verify that the user is logged in
    if ($usuario_id <= 0) {
        header('Location: ../public/login.php');
        exit;
    }

    if ($idpost > 0 && !empty($description)) {
        $db = new Conexion();
        
        $description = mysqli_real_escape_string($db->getConnection(), $description);
        
        $query = "INSERT INTO comments (description, idpost, usuario_id) 
                  VALUES ('$description', $idpost, $usuario_id)";
        $result = $db->ejecutarQuery($query);
        
        if ($result) {
            header("Location: view_post.php?id=$idpost");
            exit;
        }
    }
}

// Redirect to index if there's an error
header('Location: ../index.php');
?>