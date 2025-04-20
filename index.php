<?php
session_start(); // Iniciar la sesión
require_once './src/database/Conexion.php';

$db = new Conexion();
$query = "SELECT p.idpost, p.title, p.description, p.fecha, u.name 
          FROM posts p 
          JOIN usuario u ON p.usuario_id = u.idusuario 
          ORDER BY p.fecha DESC";
$result = $db->ejecutarQuery($query);

// Verificar si hay un usuario logueado
$usuarioLogueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// Verificar si el usuario es administrador
$isAdmin = $usuarioLogueado && $usuarioLogueado['name'] === 'holi';
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MisPosts - Grupo X</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="./assets/theme.js" defer></script>
    <style>
        /* Estilo para enlaces dentro de los posts */
        .card a {
            color: var(--text-color); /* Usa el color del texto definido en el tema */
            text-decoration: none; /* Elimina el subrayado */
        }

        .card a:hover {
            color: var(--button-background); /* Cambia el color al pasar el mouse */
            text-decoration: underline; /* Agrega subrayado al pasar el mouse */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenido a MisPosts Grupo 5</h1>
        
        <!-- Mostrar información del usuario logueado -->
        <div class="mb-3">
            <?php if ($usuarioLogueado): ?>
                <p>Sesión iniciada como: <strong><?php echo htmlspecialchars($usuarioLogueado['name']); ?></strong></p>
                <a href="./public/logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                <?php if ($isAdmin): ?>
                    <a href="./public/manage_users.php" class="btn btn-secondary btn-sm">Administrar Usuarios</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="./public/login.php" class="btn btn-primary btn-sm">Iniciar Sesión</a>
                <a href="./public/register.php" class="btn btn-outline-secondary btn-sm">Registrarse</a>
            <?php endif; ?>
        </div>


        <div class="row mt-4">
            <!-- Columna para el formulario de crear post -->
            <?php if ($usuarioLogueado): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Crear un nuevo post</h5>
                            <form action="./public/add_post.php" method="post">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Título</label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Publicar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Columna para los posts recientes -->
            <div class="col-md-8">
                <h2>Posts Recientes</h2>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<a href="./public/view_post.php?id=' . $row['idpost'] . '" class="text-color">';
                        echo '<div class="card mb-3">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                        echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
                        echo '<p class="card-text"><small class="text-muted">Por: ' . htmlspecialchars($row['name']) . 
                             ' - Fecha: ' . htmlspecialchars($row['fecha']) . '</small></p>';
                        
                        // Mostrar opciones de administración solo para el administrador
                        if ($isAdmin) {
                            echo '<a href="./public/edit_post.php?id=' . $row['idpost'] . '" class="btn btn-warning btn-sm me-2">Editar</a>';
                            echo '<a href="./public/delete_post.php?id=' . $row['idpost'] . '" class="btn btn-danger btn-sm">Eliminar</a>';
                        }

                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                } else {
                    echo '<p>No hay posts disponibles.</p>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>