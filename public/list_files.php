<?php
require_once '../src/database/Conexion.php';
session_start();

$db = new Conexion();
$query = "SELECT * FROM archivos";
$result = $db->ejecutarQuery($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivos Subidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Archivos Subidos</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Original</th>
                    <th>Fecha de Subida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($archivo = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $archivo['idarchivo']; ?></td>
                        <td><?php echo htmlspecialchars($archivo['nombre_original']); ?></td>
                        <td><?php echo $archivo['fecha_subida']; ?></td>
                        <td>
                            <a href="../<?php echo htmlspecialchars($archivo['ruta']); ?>" class="btn btn-primary btn-sm" download>
                                Descargar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="../index.php" class="btn btn-secondary">Volver</a>
    </div>
</body>
</html>