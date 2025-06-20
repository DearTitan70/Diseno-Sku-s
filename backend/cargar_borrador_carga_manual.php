<?php
require_once __DIR__ . '/auth.php';
require_login_and_role();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

require_once __DIR__ . '/../conexion.php';

// Ahora seleccionamos también la fecha de guardado
$stmt = $conn->prepare("SELECT datos_json, fecha_guardado FROM borradores_carga_manual WHERE usuario_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($datos_json, $fecha_guardado);

if ($stmt->fetch() && $datos_json) {
    // Puedes formatear la fecha si lo deseas, por ejemplo: d/m/Y H:i
    $fecha_legible = date('d/m/Y H:i', strtotime($fecha_guardado));
    echo json_encode([
        'success' => true,
        'datos' => json_decode($datos_json, true),
        'fecha_modificacion' => $fecha_legible
    ]);
} else {
    echo json_encode(['success' => false, 'datos' => null]);
}
$stmt->close();
?>