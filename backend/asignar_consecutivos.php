<?php
require_once __DIR__ . '/../backend/auth.php';
require_login_and_role(1); 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$id_inicio = intval($data['id_inicio'] ?? 0);
$id_fin = intval($data['id_fin'] ?? 0);
$sap_inicial = intval($data['sap_inicial'] ?? 0);

if ($id_inicio <= 0 || $id_fin <= 0 || $sap_inicial <= 0 || $id_fin < $id_inicio) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

require_once __DIR__ . '/../conexion.php'; 

// 1. Obtener los IDs a actualizar
$stmt = $conn->prepare("SELECT id FROM catalogo_disenos WHERE id BETWEEN ? AND ? ORDER BY id ASC");
$stmt->bind_param("ii", $id_inicio, $id_fin);
$stmt->execute();
$result = $stmt->get_result();
$ids = [];
while ($row = $result->fetch_assoc()) {
    $ids[] = $row['id'];
}
$stmt->close();

if (count($ids) === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'No se encontraron registros en ese rango']);
    exit;
}

// 2. Validar que los SAP a asignar no existan ya
$asignar_saps = [];
foreach ($ids as $i => $id) {
    $asignar_saps[] = $sap_inicial + $i;
}
$placeholders = implode(',', array_fill(0, count($asignar_saps), '?'));
$types = str_repeat('i', count($asignar_saps));
$query = "SELECT SAP FROM catalogo_disenos WHERE SAP IN ($placeholders)";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$asignar_saps);
$stmt->execute();
$result = $stmt->get_result();
$existentes = [];
while ($row = $result->fetch_assoc()) {
    $existentes[] = $row['SAP'];
}
$stmt->close();

if (count($existentes) > 0) {
    http_response_code(409);
    echo json_encode(['error' => 'Consecutivo(s) SAP ya en uso', 'existentes' => $existentes]);
    exit;
}

// 3. Asignar los SAP (transacción)
$conn->begin_transaction();
try {
    $stmt = $conn->prepare("UPDATE catalogo_disenos SET SAP = ? WHERE id = ?");
    foreach ($ids as $i => $id) {
        $nuevo_sap = $sap_inicial + $i;
        $stmt->bind_param("ii", $nuevo_sap, $id);
        $stmt->execute();
    }
    $stmt->close();
    $conn->commit();
    echo json_encode(['success' => true, 'asignados' => $asignar_saps]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Error al asignar consecutivos', 'detalle' => $e->getMessage()]);
}
?>
