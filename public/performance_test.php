<?php
require_once '../src/database/Conexion.php';
require_once '../src/models/Post.php';
require_once '../src/models/Comment.php';
session_start();

// Check if user is admin
$usuarioLogueado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$isAdmin = $usuarioLogueado && $usuarioLogueado['name'] === 'holi';

if (!$isAdmin) {
    header('Location: ../index.php');
    exit;
}

$message = '';
$db = new Conexion();
$connection = $db->getConnection();

// Function to generate random text
function generateRandomText($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ';
    $text = '';
    for ($i = 0; $i < $length; $i++) {
        $text .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $text;
}

// Function to generate posts and comments
function generateTestData($connection, $postCount, $commentsPerPost) {
    global $message;
    $userId = isset($_SESSION['usuario']['idusuario']) ? $_SESSION['usuario']['idusuario'] : 1;
    $startTime = microtime(true);
    
    // Use batch processing for better performance
    $batchSize = 100;
    $totalPosts = 0;
    $totalComments = 0;
    
    for ($p = 0; $p < $postCount; $p += $batchSize) {
        $currentBatch = min($batchSize, $postCount - $p);
        $posts = [];
        
        for ($i = 0; $i < $currentBatch; $i++) {
            $title = "Test Post " . ($p + $i + 1);
            $description = generateRandomText(200);
            $posts[] = new Post($title, $description, $userId);
        }
        
        // Bulk insert posts
        if (Post::bulkInsert($connection, $posts)) {
            $totalPosts += count($posts);
            
            // Get the last inserted post ID
            $result = $connection->query("SELECT MAX(idpost) as max_id FROM posts");
            $row = $result->fetch_assoc();
            $lastPostId = $row['max_id'];
            $firstPostId = $lastPostId - count($posts) + 1;
            
            // Add comments to the posts
            for ($postId = $firstPostId; $postId <= $lastPostId; $postId++) {
                $commentBatchSize = 50;
                
                for ($c = 0; $c < $commentsPerPost; $c += $commentBatchSize) {
                    $currentCommentBatch = min($commentBatchSize, $commentsPerPost - $c);
                    $comments = [];
                    
                    for ($j = 0; $j < $currentCommentBatch; $j++) {
                        $commentText = "Comment " . ($c + $j + 1) . " - " . generateRandomText(50);
                        $comment = new Comment($commentText, $postId, $userId);
                        $comments[] = $comment;
                    }
                    
                    if (Comment::bulkInsert($connection, $comments)) {
                        $totalComments += count($comments);
                    } else {
                        $message .= "Error adding comments batch for post $postId<br>";
                    }
                }
            }
        } else {
            $message .= "Error adding posts batch<br>";
        }
    }
    
    $endTime = microtime(true);
    $executionTime = round($endTime - $startTime, 2);
    return [
        'posts' => $totalPosts,
        'comments' => $totalComments,
        'time' => $executionTime
    ];
}

// Process actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['generate_500'])) {
        $result = generateTestData($connection, 500, 100);
        $message = "Generated {$result['posts']} posts and {$result['comments']} comments in {$result['time']} seconds.";
    } 
    else if (isset($_POST['generate_1000'])) {
        $result = generateTestData($connection, 1000, 50);
        $message = "Generated {$result['posts']} posts and {$result['comments']} comments in {$result['time']} seconds.";
    } 
    else if (isset($_POST['generate_5000'])) {
        $result = generateTestData($connection, 5000, 20);
        $message = "Generated {$result['posts']} posts and {$result['comments']} comments in {$result['time']} seconds.";
    } 
    else if (isset($_POST['delete_all'])) {
        $startTime = microtime(true);
        
        // Disable foreign keys temporarily if they're causing issues
        $connection->query("SET FOREIGN_KEY_CHECKS = 0");
        
        if (Comment::deleteAll($connection) && Post::deleteAll($connection)) {
            // Reset auto increment values
            Comment::resetAutoIncrement($connection);
            Post::resetAutoIncrement($connection);
            
            $connection->query("SET FOREIGN_KEY_CHECKS = 1");
            
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            $message = "All test data deleted and auto-increment values reset in $executionTime seconds.";
        } else {
            $message = "Error deleting test data.";
            $connection->query("SET FOREIGN_KEY_CHECKS = 1");
        }
    }
}

// Get current statistics
$postCount = $connection->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'];
$commentCount = $connection->query("SELECT COUNT(*) as count FROM comments")->fetch_assoc()['count'];

?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Testing - MisPosts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <script src="../assets/theme.js" defer></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Performance Testing Dashboard</h1>
        <a href="../index.php" class="btn btn-secondary mb-4">Volver al Inicio</a>

        <div class="alert alert-info">
            <strong>Recomendación de Software de Monitoreo:</strong> Para monitorear el rendimiento en tiempo real durante estas pruebas, se recomienda utilizar <strong>MySQL Workbench Monitor</strong> para la base de datos, y <strong>New Relic</strong> o <strong>Prometheus</strong> con <strong>Grafana</strong> para monitoreo de servidor completo. Estos proporcionarán métricas esenciales como tiempo de consulta, uso de CPU, memoria y rendimiento del sistema.
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Estadísticas Actuales</h2>
                <p>Total de posts: <strong><?php echo $postCount; ?></strong></p>
                <p>Total de comentarios: <strong><?php echo $commentCount; ?></strong></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Generar Datos de Prueba</h2>
                <div class="row">
                    <div class="col-md-4">
                        <form method="post" action="">
                            <button type="submit" name="generate_500" class="btn btn-primary btn-lg w-100 mb-3">
                                Generar 500 Posts con 100 Comentarios c/u
                            </button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="post" action="">
                            <button type="submit" name="generate_1000" class="btn btn-primary btn-lg w-100 mb-3">
                                Generar 1000 Posts con 50 Comentarios c/u
                            </button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="post" action="">
                            <button type="submit" name="generate_5000" class="btn btn-primary btn-lg w-100 mb-3">
                                Generar 5000 Posts con 20 Comentarios c/u
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 bg-danger text-white">
            <div class="card-body">
                <h2 class="card-title">Limpiar Base de Datos</h2>
                <p class="card-text">Esta acción eliminará <strong>TODOS</strong> los posts y comentarios de la base de datos y restablecerá los contadores de auto-incremento.</p>
                <form method="post" action="">
                    <button type="submit" name="delete_all" class="btn btn-warning btn-lg" onclick="return confirm('¿Estás seguro? Esta acción no se puede deshacer.')">
                        Eliminar Todos los Posts y Comentarios
                    </button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Script SQL para Limpiar la Base de Datos</h2>
                <p>Si necesitas limpiar la base de datos manualmente, puedes usar el siguiente script SQL:</p>
                <pre><code>-- Desactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar todos los registros
DELETE FROM comments;
DELETE FROM posts;

-- Restablecer los contadores de auto-incremento
ALTER TABLE comments AUTO_INCREMENT = 1;
ALTER TABLE posts AUTO_INCREMENT = 1;

-- Reactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;</code></pre>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
