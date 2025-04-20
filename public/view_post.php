<?php
session_start();
require_once '../src/database/Conexion.php';

$db = new Conexion();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: ../index.php');
    exit;
}

// Obtener los detalles del post y el usuario que lo creó
$query = "SELECT p.*, u.name AS usuario_name 
          FROM posts p 
          JOIN usuario u ON p.usuario_id = u.idusuario 
          WHERE p.idpost = $id";
$result = $db->ejecutarQuery($query);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    header('Location: ../index.php');
    exit;
}

// Verificar si el usuario está logueado
$usuarioLogueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// Verificar si el usuario es administrador
$isAdmin = $usuarioLogueado && $usuarioLogueado['name'] === 'holi';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - MisPosts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/style.css">
    <script src="./assets/theme.js" defer></script>
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php" class="btn btn-outline-secondary mb-3">← Volver</a>
        
        <div class="card">
            <div class="card-body">
                <h1 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="text-muted">Por: <?php echo htmlspecialchars($post['usuario_name']); ?> | 
                   <?php echo htmlspecialchars($post['fecha']); ?></p>
                <div class="card-text mt-4">
                    <?php echo nl2br(htmlspecialchars($post['description'])); ?>
                </div>
            </div>
        </div>
        
        <div class="mt-5">
            <h3>Comentarios</h3>
            <?php
            // Obtener los comentarios y el usuario que los creó
            $query = "SELECT c.*, u.name AS usuario_name 
                      FROM comments c 
                      JOIN usuario u ON c.usuario_id = u.idusuario 
                      WHERE c.idpost = $id 
                      ORDER BY c.fecha DESC";
            $commentsResult = $db->ejecutarQuery($query);
            
            if ($commentsResult && mysqli_num_rows($commentsResult) > 0) {
                while ($comment = mysqli_fetch_assoc($commentsResult)) {
                    echo '<div class="card mb-2">';
                    echo '<div class="card-body">';
                    echo '<p class="card-text">' . nl2br(htmlspecialchars($comment['description'])) . '</p>';
                    echo '<p class="card-text"><small class="text-muted">Por: ' . 
                         htmlspecialchars($comment['usuario_name']) . ' | Fecha: ' . 
                         htmlspecialchars($comment['fecha']) . '</small></p>';
                    
                    // Mostrar botón de eliminar solo para el administrador
                    if ($isAdmin) {
                        echo '<a href="./delete_comment.php?id=' . $comment['idcomment'] . '&post_id=' . $id . '" class="btn btn-danger btn-sm">Eliminar</a>';
                    }

                    echo '</div></div>';
                }
            } else {
                echo '<p>No hay comentarios todavía.</p>';
            }
            ?>
            
            <?php if ($usuarioLogueado): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5>Añadir comentario</h5>
                        <form action="add_comment.php" method="post">
                            <input type="hidden" name="idpost" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Comentar</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-danger">Debes <a href="../public/login.php">iniciar sesión</a> para añadir un comentario.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>