<?php
require_once '../src/database/Conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['name'] !== 'holi') {
    header('Location: ../index.php');
    exit;
}

$idcomment = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($idcomment > 0) {
    $db = new Conexion();

    // Eliminar el comentario
    $queryDeleteComment = "DELETE FROM comments WHERE idcomment = $idcomment";
    $db->ejecutarQuery($queryDeleteComment);

    $db->cerrarConexion();
}

// Redirigir de vuelta al post
header("Location: view_post.php?id=$post_id");
exit;
?>