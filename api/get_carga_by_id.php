<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de carga no válido.']);
    exit;
}

$conn->set_charset('utf8mb4');

$stmt = $conn->prepare("SELECT * FROM cargas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $carga = $result->fetch_assoc();
    echo json_encode(['success' => true, 'carga' => $carga]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se encontró la carga con el ID proporcionado.']);
}

$stmt->close();
$conn->close();
?>

