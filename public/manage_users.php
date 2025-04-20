<?php
require_once '../src/database/Conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['name'] !== 'holi') {
    header('Location: ../index.php');
    exit;
}

$db = new Conexion();

// Actualizar el estado de habilitación del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idusuario = isset($_POST['idusuario']) ? intval($_POST['idusuario']) : 0;
    $habilitado = isset($_POST['habilitado']) ? intval($_POST['habilitado']) : 0;

    if ($idusuario > 0) {
        $query = "UPDATE usuario SET habilitado = $habilitado WHERE idusuario = $idusuario";
        $db->ejecutarQuery($query);
    }
}

// Obtener la lista de usuarios
$query = "SELECT idusuario, name, email, habilitado FROM usuario";
$result = $db->ejecutarQuery($query);
?>

<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/style.css">
    <script src="./assets/theme.js" defer></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="display-4">Administrar Usuarios</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Habilitado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $user['idusuario']; ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['habilitado'] ? 'Sí' : 'No'; ?></td>
                            <td>
                                <form action="manage_users.php" method="post" style="display:inline;">
                                    <input type="hidden" name="idusuario" value="<?php echo $user['idusuario']; ?>">
                                    <input type="hidden" name="habilitado" value="<?php echo $user['habilitado'] ? 0 : 1; ?>">
                                    <button type="submit" class="btn btn-<?php echo $user['habilitado'] ? 'danger' : 'success'; ?> btn-sm">
                                        <?php echo $user['habilitado'] ? 'Deshabilitar' : 'Habilitar'; ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <a href="../index.php" class="btn btn-secondary">Volver</a>
    </div>
</body>
</html>