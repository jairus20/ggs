<?php
require_once '../src/database/Conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['name'] !== 'holi') {
    header('Location: ../index.php');
    exit;
}

$idpost = isset($_GET['id']) ? intval($_GET['id']) : 0;
$db = new Conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    if (!empty($title)) {
        $title = mysqli_real_escape_string($db->getConnection(), $title);
        $query = "UPDATE posts SET title = '$title' WHERE idpost = $idpost";
        $db->ejecutarQuery($query);
        $db->cerrarConexion();
        header('Location: ../index.php');
        exit;
    }
}

$query = "SELECT title FROM posts WHERE idpost = $idpost";
$result = $db->ejecutarQuery($query);
$post = mysqli_fetch_assoc($result);
$db->cerrarConexion();
?>

<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Post</title>
    <link rel="stylesheet" href="./assets/style.css">
    <script src="./assets/theme.js" defer></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Post</h1>

        <form action="" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>