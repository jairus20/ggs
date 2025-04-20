<?php
session_start();

require_once '../src/database/Conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $db = new Conexion();
    $query = "SELECT * FROM usuario WHERE email = '$email'";
    $result = $db->ejecutarQuery($query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verificar si la cuenta está deshabilitada
        if (!$user['habilitado']) {
            $error = "La cuenta está deshabilitada. Contacta al administrador.";
        } elseif (password_verify($password, $user['password'])) {
            // Establecer la sesión si la contraseña es correcta
            $_SESSION['usuario'] = [
                'idusuario' => $user['idusuario'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            header('Location: ../index.php');
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MisPosts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/style.css">
    <script src="./assets/theme.js" defer></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Iniciar Sesión</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            <a href="register.php" class="btn btn-link">¿No tienes una cuenta? Regístrate</a>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>