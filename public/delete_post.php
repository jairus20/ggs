<?php
require_once '../src/database/Conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['name'] !== 'holi') {
    header('Location: ../index.php');
    exit;
}

$idpost = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idpost > 0) {
    $db = new Conexion();

    // Eliminar los comentarios asociados al post
    $queryDeleteComments = "DELETE FROM comments WHERE idpost = $idpost";
    $db->ejecutarQuery($queryDeleteComments);

    // Eliminar el post
    $queryDeletePost = "DELETE FROM posts WHERE idpost = $idpost";
    $db->ejecutarQuery($queryDeletePost);

    $db->cerrarConexion();
}

header('Location: ../index.php');
exit;
?>