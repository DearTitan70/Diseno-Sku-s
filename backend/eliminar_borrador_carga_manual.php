<?php

require_once __DIR__ . '/auth.php'; // Asegúrate de que el usuario esté autenticado
require_once __DIR__ . '/../conexion.php';   // Archivo de conexión a la base de datos
require_login_and_role();

header('Content-Type: application/json');

// Permitir solo métodos POST para mayor seguridad
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Validar usuario autenticado
$usuario_id = $_SESSION['user_id'] ?? null;
if (!$usuario_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

// Eliminar el borrador del usuario autenticado
try {
    // Ajusta el nombre de la tabla según tu base de datos
    $stmt = $conn->prepare("DELETE FROM borradores_carga_manual WHERE usuario_id = ?");
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Borrador eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró el borrador para eliminar']);
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>