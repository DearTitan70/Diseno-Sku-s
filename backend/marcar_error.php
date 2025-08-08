<?php
require_once __DIR__ . '/auth.php';
require_login_and_role();
date_default_timezone_set('America/Bogota');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'], $data['nombre'], $data['motivo_error'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$id = intval($data['id']);
$nombre = trim($data['nombre']);
$motivo_error = trim($data['motivo_error']);
$marcar_todos = !empty($data['marcar_todos']);

require_once __DIR__ . '/../conexion.php'; 

try {
    if ($marcar_todos) {
        $stmt = $conn->prepare("UPDATE catalogo_disenos SET ESTADO='X', MOTIVO_ERROR=?  WHERE NOMBRE=?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("ss", $motivo_error, $nombre);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        echo json_encode(['success' => true, 'message' => "$affected registros marcados como error."]);
    } else {
        // Actualiza solo el registro por ID
        $stmt = $conn->prepare("UPDATE catalogo_disenos SET ESTADO='X', MOTIVO_ERROR=? WHERE ID=?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("si", $motivo_error, $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        echo json_encode(['success' => true, 'message' => "Material marcado como error."]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => "Error en la base de datos: " . $e->getMessage()]);
}