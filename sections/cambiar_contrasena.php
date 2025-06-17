<?php
session_start();
require_once '../conexion.php'; // Ajusta la ruta según tu estructura

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$mensaje = '';
$error = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['actual'] ?? '';
    $nueva = $_POST['nueva'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    // Validaciones básicas
    if (empty($actual) || empty($nueva) || empty($confirmar)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($nueva !== $confirmar) {
        $error = "La nueva contraseña y la confirmación no coinciden.";
    } elseif (strlen($nueva) < 6) {
        $error = "La nueva contraseña debe tener al menos 6 caracteres.";
    } else {
        // Obtener el hash de la contraseña actual desde la base de datos
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($hashActual);
        $usuarioEncontrado = $stmt->fetch();
        $stmt->close();

        if ($usuarioEncontrado) {
            // Verificar la contraseña actual
            if (password_verify($actual, $hashActual)) {
                // Actualizar la contraseña
                $nuevoHash = password_hash($nueva, PASSWORD_DEFAULT);

                $stmt2 = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt2->bind_param("si", $nuevoHash, $userId);
                if ($stmt2->execute()) {
                    $mensaje = "¡Contraseña cambiada exitosamente!";
                } else {
                    $error = "Error al actualizar la contraseña. Intenta de nuevo.";
                }
                $stmt2->close();
            } else {
                $error = "La contraseña actual es incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link rel="icon" type="image/x-icon" href="../img/FDS_Favicon.png">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #F9F3E5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 32px 28px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            color: #879683;
            margin-bottom: 18px;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 14px;
            margin-bottom: 6px;
            color: #333;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            font-size: 1em;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #879683;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #5A6B58;
        }
        .msg {
            margin-top: 12px;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
        }
        .msg.success {
            background: #e6ffed;
            color: #237804;
            border: 1px solid #b7eb8f;
        }
        .msg.error {
            background: #fff1f0;
            color: #cf1322;
            border: 1px solid #ffa39e;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #879683;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Cambiar Contraseña</h2>
    <?php if ($mensaje): ?>
        <div class="msg success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php elseif ($error): ?>
        <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <label for="actual">Contraseña actual:</label>
        <input type="password" id="actual" name="actual" required>

        <label for="nueva">Nueva contraseña:</label>
        <input type="password" id="nueva" name="nueva" required>

        <label for="confirmar">Confirmar nueva contraseña:</label>
        <input type="password" id="confirmar" name="confirmar" required>

        <button type="submit" class="btn">Cambiar contraseña</button>
    </form>
    <a href="index.php">Volver al menú principal</a>
</div>
</body>
</html>