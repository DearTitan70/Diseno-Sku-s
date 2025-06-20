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

$data = json_decode(file_get_contents('php://input'), true);
$datos_json = json_encode($data['datos'] ?? []);

require_once __DIR__ . '/../conexion.php'; 

$stmt = $conn->prepare("
    INSERT INTO borradores_carga_manual (usuario_id, datos_json, fecha_guardado)
    VALUES (?, ?, NOW())
    ON DUPLICATE KEY UPDATE datos_json = VALUES(datos_json), fecha_guardado = NOW()
");
$stmt->bind_param('is', $user_id, $datos_json);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}